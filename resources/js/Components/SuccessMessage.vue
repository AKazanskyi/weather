<template>
    <div v-if="text" class="bg-green-200 text-green-800">
        <slot :text="text">
            <div class="flex max-w-7xl mx-auto">
                <div
                    class="
                        py-6
                        px-4
                        sm:px-6
                        lg:px-8
                        font-semibold
                        text-center
                        w-full
                    "
                >
                    {{ text }}
                </div>

                <div
                    class="font-bold flex items-center cursor-pointer mr-6"
                    @click="text = false"
                >
                    <i class="fal fa-times"></i>
                </div>
            </div>
        </slot>
    </div>
</template>

<script>
export default {
    props: {
        message: [String, Boolean],
    },

    data() {
        return {
            text: false,
        };
    },

    watch: {
        message: {
            immediate: true,
            handler(value) {
                this.text = value;
            },
        },
    },
};
</script>
