<template>
    <div v-if="hasErrors" class="bg-red-200 text-red-800">
        <div class="flex max-w-7xl mx-auto">
            <ul class="py-4 px-4 font-semibold w-full">
                <li v-for="(message, index) in errors" :key="index">
                    {{ message }}
                </li>
            </ul>

            <div
                class="font-bold flex items-center cursor-pointer mr-6"
                @click="errors = false"
            >
                <i class="fal fa-times"></i>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    computed: {
        hasErrors() {
            if (typeof this.errors === "object") {
                return Object.keys(this.errors).length;
            }

            return false;
        },

        errors: {
            get() {
                return this.$page.props.errors;
            },

            set(value) {
                this.$page.props.errors = value;
            },
        },
    },
};
</script>
