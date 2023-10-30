<template>
    <div class="relative">
        <input
            type="text"
            id="search"
            v-model="query"
            @keyup="handle"
            class="
                form-input
                border border-gray-200
                shadow-sm
                rounded-full
                h-8
                text-sm
            "
            placeholder="Enter city"
        />

        <div
            v-if="results.length"
            class="
                absolute
                top-full
                rounded
                bg-white
                border border-gray-200
                w-full
                z-50
            "
        >
            <ul class="divide-y divide-gray-200 w-full">
                <div
                    v-for="(result, key) in results"
                    @click="setSearchTerm(result)"
                    class="p-2 hover:bg-gray-200 cursor-pointer"
                    :class="{ 'bg-gray-200': key === index }"
                >
                    {{ result.formatted_address }}
                </div>
            </ul>
        </div>
    </div>
</template>

<script>


import _ from "lodash";

export default {
    data() {
        return {
            query: null,
            results: [],
            index: 0,
        };
    },

    mounted() {
        window.addEventListener("keydown", this.focus);
    },

    beforeDestroy() {
        window.removeEventListener("keydown", this.focus);
    },

    methods: {
        focus(e) {
            if (e.key === "/") {
                e.preventDefault();
                document.querySelector("#search").focus();
            }
        },

        handle(e) {
            if ([13].indexOf(e.keyCode) >= 0) {
                this.search(e);
            }
        },

        search: _.throttle(function () {
            if (
                this.query === null ||
                (typeof this.query === "string" && this.query.length === 0)
            ) {
                this.results = [];
                return;
            }

            const self = this;

            axios
                .get(route("dashboard.geo.search", {query: this.query}))
                .then(({data}) => {
                    self.results = data.results ? data.results : [];
                });
        }, 500),

        setSearchTerm(result) {

            const vc = this
            const data = {
                lat: result.geometry.location.lat,
                lng: result.geometry.location.lng,
                name: result.formatted_address,

            }
            axios
                .post(route("cities.store", data))
                .then(({data}) => {
                    if(data.status === "SUCCESS"){
                        this.$emit('onSuccessMessage', data.msg);
                    } else {
                        this.$emit('onErrorMessage', data.msg);
                    }
                    vc.results = []
                }).catch(
                function (error) {
                    this.$emit('onErrorMessage', 'Error during city creatio');
                    return Promise.reject(error)
                }
            );
        },
    },
};
</script>
<style scoped>
.form-input {
    min-width: 300px;
}
</style>
