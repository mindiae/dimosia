<template>
    <button :type="type" :style='{color: color}' class="inline-flex items-center my-0.5 mx-0.5 px-2 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs  uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
        <slot></slot>
    </button>
</template>

<script>
    import { defineComponent } from 'vue'

    export default defineComponent({
        props: {
            type: {
                type: String,
                default: 'submit',
            },
            color: {
                type: String,
                default: 'white',
            }
        }
    })
</script>
