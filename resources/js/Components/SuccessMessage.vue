<template>
    <div v-if="text" class="msg-container success txt-white mb-4">
        <slot :text="text">
            <div class="d-flex">
                <div class="d-flex justify-content-center align-items-center">
                    <span class="icon-success me-3 txt-green ico"></span>{{ text }}
                </div>
            </div>
        </slot>
    </div>
</template>

<script>
export default {
    props: {
        message: [String, Boolean],
        timeout: [Number, Boolean]
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

    mounted() {
        if(this.timeout){
            let vc = this
            setTimeout(() => {
                vc.text = false;
            }, vc.timeout);
        }
    }
};
</script>
