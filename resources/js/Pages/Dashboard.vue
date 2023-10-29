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
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <Welcome />
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
    components: {
        GeoSearchInput,
        AppLayout,
        Welcome,
        ErrorMessage,
        SuccessMessage,
    },

    mounted() {
    },

    data() {
        return {
            url: window.location.pathname,
            messages: [],
            errorMessages: [],
        };
    },

    methods: {
        addSuccessMessage(msg)
        {
           this.messages.push(msg)
        },
        addErrorsMessage(msg)
        {
            this.errorMessages.push(msg)
        },
    },
};

</script>

