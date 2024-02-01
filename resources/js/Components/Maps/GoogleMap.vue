<template>
  <GoogleMap
    :api-key="googleAutocompleteApiKey"
    style="width: 100%; height: 500px"
    :center="center"
    :zoom="zoomAmount"
  >
    <MarkerCluster>
      <Marker v-for="(location, i) in locations" :options="{ position: location }" :key="i" />
    </MarkerCluster>
  </GoogleMap>
</template>

<script>
import { defineComponent, ref, watch } from 'vue'
import { GoogleMap, Marker, MarkerCluster } from 'vue3-google-map'

export default defineComponent({
  components: { GoogleMap, Marker, MarkerCluster },
  props: {
    markers: {
      type: Array,
      required: false,
      default: () => []
    }
  },
  setup(props) {
    const googleAutocompleteApiKey = import.meta.env.GOOGLE_AUTOCOMPLETE_API_KEY;
    const locations = ref(props.markers);
    const zoomAmount = ref(1);
    let center;

    const updateCenter = () => {
      if (locations.value.length > 0) {
        // Calculate the average latitude and longitude
        const sumLat = locations.value.reduce((acc, loc) => acc + loc.lat, 0)
        const sumLng = locations.value.reduce((acc, loc) => acc + loc.lng, 0)
        center = {
          lat: sumLat / locations.value.length,
          lng: sumLng / locations.value.length,
        };
        zoomAmount.value = 3;
      } else {
        // Set default center for the world map view
        center = { lat: 0, lng: 0 };
        zoomAmount.value = 2; 
      }
    };

    // Initial call
    updateCenter();

    // Watch for changes in markers prop
    watch(() => props.markers, (newMarkers) => {
      locations.value = newMarkers;
      updateCenter(); // Call updateCenter when markers change
    });

    return { center, locations, zoomAmount };
  },
})
</script>