<template>
  <app-layout title="Bible">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Bible') }}
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div>
            <div class="bg-gray-200 bg-opacity-25 border-b border-gray-200">
              Old Testament:
              <template v-for="book in books">
                <jet-button :title="book.long_name" :color="book.book_color" v-if="book.book_number < 470"
                  @click="getAllVerses(book.book_number, 1)">
                  {{ book.short_name }}
                </jet-button>
              </template>
            </div>
            <div class="bg-gray-200 bg-opacity-25 border-b border-gray-200">
              New Testament:
              <template v-for="book in books">
                <jet-button :title="book.long_name" :color="book.book_color" v-if="book.book_number >= 470"
                  @click="getAllVerses(book.book_number, 1)">
                  {{ book.short_name }}
                </jet-button>
              </template>
            </div>
            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">

            <input type="checkbox" id="checkbox" v-model="paragraph" />
            <label for="checkbox"> paragraph mode {{ paragraph ? "enabled" : "disabled" }}</label>


              <div class="mt-8 text-2xl" :key="forceUpdater">
                <div class="md:float-left" v-for="parallel_connection in parallel_connections" :key="parallel_connection">
                  <jet-dropdown align="left" width="64">
                    <template #trigger>
                      <span class="inline-flex rounded-md">
                        <button type="button"
                          class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                          <template v-if="parallel_connection == 1">{{ connection }}</template>
                          <template v-else-if="parallel_connection == 2">{{ secondConnection }}</template>
                          <template v-else-if="parallel_connection == 3">{{ thirdConnection }}</template>
                          <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                              clip-rule="evenodd" />
                          </svg>
                        </button>
                      </span>
                    </template>
                    <template #content>
                      <template v-for="(description, abbreviation) in connections" :key="abbreviation">
                        <jet-dropdown-link as="button"
                          @click="getChapterVerses(abbreviation, book_number, chapter, parallel_connection); getBooks(abbreviation, parallel_connection)">
                          {{ `${abbreviation}, ${description}` }}
                        </jet-dropdown-link>
                      </template>
                    </template>
                  </jet-dropdown>
                </div>
              </div>

              <jet-dropdown v-if="parallel_connections < 3" class="md:float-left" align="left" width="64">
                <template #trigger>
                  <span class="inline-flex rounded-md">
                    <button type="button"
                      class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                      +
                      <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                          clip-rule="evenodd" />
                      </svg>
                    </button>
                  </span>
                </template>
                <template #content>
                  <template v-for="(description, abbreviation) in connections" :key="abbreviation">
                    <jet-dropdown-link as="button" v-if="abbreviation != connection"
                      @click="parallel_connections++; getChapterVerses(abbreviation, book_number, chapter, parallel_connections); $cookies.set('parallel_connections', parallel_connections)">
                      {{ `${abbreviation}, ${description}` }}
                    </jet-dropdown-link>
                  </template>
                </template>
              </jet-dropdown>

              <div class="clear-left" v-if="books[book_number]">{{ books[book_number].long_name }}</div>

              <jet-dropdown id="scrollHere" class="clear-left" align="left" width="50">
                <template #trigger>
                  <span class="inline-flex rounded-md">
                    <button type="button"
                      class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                      {{ chapter }}
                      <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                          clip-rule="evenodd" />
                      </svg>
                    </button>
                  </span>
                </template>
                <template #content>
                  <template v-if="maxChapters[book_number]" v-for="chapter in maxChapters[book_number].max_chapter"
                    :key="chapter">
                    <jet-button as="button" @click="getAllVerses(book_number, chapter)">
                      {{ chapter }}
                    </jet-button>
                  </template>

                </template>
              </jet-dropdown>



              <div v-if="paragraph" class="clear-left mt-6 text-gray-500">
                <template v-for="verse in verses" :key="verse.book_number + verse.chapter + verse.verse">
                  <span v-if="typeof verse.text === 'string'">
                    <span class="align-super">
                      {{ verse.verse }}
                    </span>
                    <span>
                      {{ verse.text }}
                    </span>
                    {{ ' ' }}
                  </span>
                  <span v-else>
                    <span class="align-super">
                      {{ verse.verse }}
                    </span>
                    <verse-text :data="verse.text"></verse-text>
                  </span>
                </template>
              </div>

              <div v-else class="bg-white rounded-md shadow overflow-x-auto">
                <table class="w-full">
                  <tr class="text-left font-bold">
                    <th class="pb-4 pt-6 px-6">#</th>
                    <th class="pb-4 pt-6 px-6">{{ connection }}</th>
                    <th class="pb-4 pt-6 px-6">{{ secondConnection }}</th>
                    <th class="pb-4 pt-6 px-6">{{ thirdConnection }}</th>
                  </tr>
                  <tr v-for="verse in verses" :key="verse.book_number + verse.chapter + verse.verse" class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t min-w-fit">
                      {{ verse.verse }}
                    </td>
                    <td v-if="verse.verse" class="border-t min-w-fit sm:min-w-full">
                      <span v-if="typeof verse.text === 'string'">{{ verse.text }}</span>
                      <verse-text v-else :data="verse.text"></verse-text>
                    </td>
                    <td v-if="parallel_connections > 1 && secondVerses[verse.verse]" class="border-t w-100">
                      <span v-if="typeof secondVerses[verse.verse].text === 'string'">{{ secondVerses[verse.verse].text }}</span>
                      <verse-text v-else :data="secondVerses[verse.verse].text"></verse-text>
                    </td>
                    <td v-if="parallel_connections > 2 && thirdVerses[verse.verse]" class="border-t w-100">
                      <span v-if="typeof thirdVerses[verse.verse].text === 'string'">{{ thirdVerses[verse.verse].text }}</span>
                      <verse-text v-else :data="thirdVerses[verse.verse].text"></verse-text>
                    </td>
                  </tr>
                </table>
              </div>
              <div class="flex flex-wrap -mb-1">
                <template v-if="maxChapters[book_number]" v-for="chapter_number in maxChapters[book_number].max_chapter">
                  <jet-button v-if="chapter == chapter_number" as="button" disabled>
                    {{ chapter_number }}
                  </jet-button>
                  <jet-button v-scroll-to="'#scrollHere'" v-else as="button" @click="getAllVerses(book_number, chapter_number)">
                    {{ chapter_number }}
                  </jet-button>
                </template>
              </div>
              
            </div>
          </div>

        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import { defineComponent } from 'vue'
import { Head, Link } from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import JetDropdown from '@/Jetstream/Dropdown.vue'
import JetDropdownLink from '@/Jetstream/DropdownLink.vue'
import JetButton from '@/Jetstream/Button.vue'
import VerseText from '@/Dimosia/VerseText.vue'

export default defineComponent({
  components: {
    Head,
    Link,
    AppLayout,
    JetDropdown,
    JetDropdownLink,
    JetButton,
    VerseText
  },
  data() {
    return {
      paragraph: true,
      parallel_connections: 1,
      connection: 'OGB',
      secondConnection: "",
      thirdConnection: "",
      book_number: '10',
      chapter: '1',
      verses: "",
      secondVerses: "",
      thirdVerses: "",
      maxChapters: "",
      books: "",
      forceUpdater: 0,
    }
  },
  mounted() {
    this.getData()
    this.getAllVerses(this.book_number, this.chapter)
    this.getBooks(this.connection)
  },
  watch: {
    verses(){
      if (this.parallel_connections > 1){
        this.parallel_connections--
        this.parallel_connections++
      }
    },
    paragraph(newParagraph, oldParagraph){
      this.$cookies.set("paragraph", newParagraph)
    }
  },
  methods: {
    getData(){
      let book_number = this.$cookies.get("book_number")
      this.book_number = book_number ? book_number : this.book_number
      let chapter = this.$cookies.get("chapter")
      this.chapter = chapter ? chapter : this.chapter
      let connection = this.$cookies.get("connection")
      this.connection = connection ? connection : this.connection
      let secondConnection = this.$cookies.get("secondConnection")
      this.secondConnection = secondConnection ? secondConnection : this.secondConnection
      let thirdConnection = this.$cookies.get("thirdConnection")
      this.thirdConnection = thirdConnection ? thirdConnection : this.thirdConnection
      let parallel_connections = this.$cookies.get("parallel_connections")
      this.parallel_connections = parallel_connections ? parallel_connections : this.parallel_connections
      let paragraph = this.$cookies.get("paragraph")
      this.paragraph = paragraph ? paragraph : false
    },
    getChapterVerses(abbr, book_number, chapter_number, number = 1) {
      axios
        .get(`http://localhost:8000/api/verses/${abbr}/${book_number}/${chapter_number}`)
        .then(response => {
          this.book_number = book_number
          this.$cookies.set("book_number", book_number)
          this.chapter = chapter_number
          this.$cookies.set("chapter", chapter_number)
          if (number === 1) {
            this.connection = abbr
            this.$cookies.set("connection", abbr)
            this.verses = response.data.chapter
          } else if (number === 2) {
            this.secondConnection = abbr
            this.$cookies.set("secondConnection", abbr)
            this.secondVerses = response.data.chapter
          } else if (number === 3) {
            this.thirdConnection = abbr
            this.$cookies.set("thirdConnection", abbr)
            this.thirdVerses = response.data.chapter
          }
        })
        .catch(error => console.log(error))
    },
    getAllVerses(book_number, chapter_number) {
      for (let i = 0 ; i < this.parallel_connections ; i++) {
        let abbr
        if (i === 0){
          abbr = this.connection
        } else if (i === 1) {
          abbr = this.secondConnection
        } else if (i === 2) {
          abbr = this.thirdConnection
        }
        this.getChapterVerses(abbr, book_number, chapter_number, i + 1)
      }
    },
    getBooks(abbr) {
      axios
        .get(`http://localhost:8000/api/books/${abbr}`)
        .then(response => {
          this.books = response.data.books
          this.maxChapters = response.data.maxChapters
        })
        .catch(error => console.log(error))
    }
  },
  props: {
    connections: Object
  }
})
</script>
