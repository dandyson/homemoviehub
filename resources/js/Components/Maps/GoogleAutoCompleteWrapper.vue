<template>
    <div>
        <vue-google-autocomplete
            @keydown.enter.prevent
            v-if="isGoogleMapsLoaded"
            :id="id"
            :classname="classname"
            :placeholder="placeholder"
            :disabled="disabled"
            :types="types"
            :country="country"
            @placechanged="handlePlaceChanged"
        />
        <p v-else class="text-white">Loading Google Maps...</p>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import VueGoogleAutocomplete from 'vue-google-autocomplete';

export default {
    components: {
        VueGoogleAutocomplete
    },
    props: {
        id: {
            type: String,
            required: true
        },
        classname: {
            type: String,
            default: ''
        },
        placeholder: {
            type: String,
            default: 'Start typing'
        },
        disabled: {
            type: Boolean,
            default: false
        },
        types: {
            type: String,
            default: 'address'
        },
        country: {
            type: [String, Array],
            default: null
        }
    },
    setup(props, { emit }) {
        const isGoogleMapsLoaded = ref(false);

        const checkGoogleMaps = () => {
            if (typeof google !== 'undefined' && typeof google.maps !== 'undefined' && typeof google.maps.places !== 'undefined' && typeof google.maps.places.Autocomplete !== 'undefined') {
                isGoogleMapsLoaded.value = true;
            } else {
                setTimeout(checkGoogleMaps, 100);
            }
        };

        onMounted(() => {
            checkGoogleMaps();
        });

        const handlePlaceChanged = (place) => {
            emit('placechanged', place);
        };

        return {
            isGoogleMapsLoaded,
            handlePlaceChanged
        };
    }
};
</script>

<style scoped>
/* Add any styles specific to your wrapper component here */
</style>
