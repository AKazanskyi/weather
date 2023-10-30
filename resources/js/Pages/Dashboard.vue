<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>

            <div
                class="search hidden md:flex justify-center items-center relative"
            >
                <geo-search-input
                    @onSuccessMessage="addSuccessMessage"
                    @onErrorMessage="addErrorsMessage">
                </geo-search-input>
            </div>
        </template>

        <success-message
            v-for="(message, key) in messages"
            :key="key"
            :message="message"
            :timeout="5000"
        ></success-message>
        <error-message
            v-for="(message, key) in errorMessages"
            :key="key"
            :message="message"
            :timeout="5000"
        ></error-message>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="cities.length > 0" class="flex flex-col w-full justify-center items-center ">

                    <div class="card-list p-3 w-full max-w-sm relative" v-for="city in cities" :key="city.id">

                        <div class="text-2xl font-medium text-gray-900 text-center cursor-pointer absolute top-5 right-5" @click="deleteCity(city)">X</div>

                        <div class="flex flex-col h-full bg-white overflow-hidden shadow-md rounded-lg border-2 p-6 lg:p-8">
                            <p class="leading-relaxed font-medium text-gray-900 text-center text-xl">
                               {{city.name}}
                            </p>

                            <p class="leading-relaxed font-medium text-gray-900 text-center">
                               Temperature: {{city.weather.temperature}} C
                            </p>

                            <p class="leading-relaxed font-medium text-gray-900 text-center">
                                UV: {{city.weather.uv}}
                            </p>

                            <p class="leading-relaxed font-medium text-gray-900 text-center">
                                Precipitation: {{city.weather.precipitation}}
                            </p>
                        </div>

                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8" v-if="cities.length === 0">
                    <p class="leading-relaxed text-2xl font-medium text-gray-900 text-center">
                        You did not add any city yet. Please search and click enter
                    </p>
                </div>

            </div>
        </div>
    </AppLayout>
</template>


<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import GeoSearchInput from '@/Components/GeoSearchInput.vue';
import ErrorMessage from "@/Components/ErrorMessage.vue";
import SuccessMessage from "@/Components/SuccessMessage.vue";
export default {

    props: ["cities"],

    components: {
        GeoSearchInput,
        AppLayout,
        Welcome,
        ErrorMessage,
        SuccessMessage,
    },

    mounted() {
        this.startInterval();
    },

    data() {
        return {
            url: window.location.pathname,
            messages: [],
            errorMessages: [],
            interval:null
        };
    },

    beforeUnmount() {
        this.endInterval();
    },

    methods: {
        startInterval() {
            this.interval = setInterval(this.reloadData, 20000);
        },
        endInterval() {
            clearInterval(this.interval);
            this.interval = null;
        },
        deleteCity(city){
            const vc = this
            const data = {
                city: city.id,
            }
            axios
                .delete(route("cities.destroy", data))
                .then(({data}) => {
                    if(data.status === "SUCCESS"){
                        vc.messages.push(data.msg)
                    } else {
                        vc.errorMessages.push(data.msg)
                    }
                }).catch(
                function (error) {
                    this.errorMessages.push('Error deling record')
                    return Promise.reject(error)
                }
            );
        },
        addSuccessMessage(msg)
        {
           this.messages.push(msg)
        },
        addErrorsMessage(msg)
        {
            this.errorMessages.push(msg)
        },
        reloadData() {
            this.timer = setTimeout(() => {
                this.$inertia.visit(
                    window.location.pathname,
                    {
                        only: ["cities"],
                    }
                );
            }, 2000);
        },

    },
};

</script>

