<template>
    <AppLayout title="Profile">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Weather settings
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">

                <div>
                    <UpdateWeatherSettingsForm :user="$page.props.auth.user" :settings="settings" />

                    <SectionBorder />
                </div>

            </div>
        </div>
    </AppLayout>
</template>


<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import UpdateWeatherSettingsForm from "@/Pages/WeatherSettings/Partials/UpdateWeatherSettingsForm.vue";

export default {

    props: ["user", "settings"],

    components: {
        AppLayout,
        SectionBorder,
        UpdateWeatherSettingsForm,
    },

    mounted() {
    },

    data() {
        return {
            url: window.location.pathname,
            pause_until: null,
            max_uv: null,
            max_pr: null
        };
    },

    methods: {
        updateSettings(){
            const vc = this
            const data = {
                pause_until: this.pause_until,
                max_uv: this.max_uv,
                max_pr: this.max_pr
            }
            axios
                .delete(route("settings.update", data))
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
    },
};
</script>
