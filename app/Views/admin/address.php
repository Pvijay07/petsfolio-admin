<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Google Maps Address Fetch</title>
</head>
<body>
  <h3>Google Maps Address Lookup</h3>
  <input type="text" id="addressInput" placeholder="Enter an address" />
  <button id="fetchBtn">Fetch Address Details</button>

  <div id="results">
    <p><strong>Address:</strong> <span id="address"></span></p>
    <p><strong>City:</strong> <span id="city"></span></p>
    <p><strong>State:</strong> <span id="state"></span></p>
    <p><strong>Country:</strong> <span id="country"></span></p>
    <p><strong>Zip Code:</strong> <span id="zipCode"></span></p>
    <p><strong>Area:</strong> <span id="area"></span></p>
  </div>

  <script>
    const GOOGLE_MAPS_API_KEY = 'AIzaSyDTo-C-B9eHT9xgpyfc40tNuaT4gXx2eEA';

    async function fetchCoordinatesFromAddress(address) {
      try {
        const response = await fetch(
          `https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(address)}&key=${GOOGLE_MAPS_API_KEY}`
        );
        const data = await response.json();

        if (data.results.length > 0) {
          const location = data.results[0].geometry.location;
          const { lat, lng } = location;
          fetchAddress(lat, lng);
        } else {
          console.error('No coordinates found for the address:', data);
          document.getElementById("address").textContent = "No coordinates found";
        }
      } catch (error) {
        console.error('Error in fetching coordinates:', error);
        document.getElementById("address").textContent = "Error fetching coordinates";
      }
    }

    async function fetchAddress(latitude, longitude) {
      try {
        const response = await fetch(
          `https://maps.googleapis.com/maps/api/geocode/json?latlng=${latitude},${longitude}&key=${GOOGLE_MAPS_API_KEY}`
        );
        const data = await response.json();

        if (data.results.length > 0) {
          const addressComponents = data.results[0].address_components;
          let city = '', state = '', country = '', postalCode = '';

          addressComponents.forEach(component => {
            if (component.types.includes('locality')) city = component.long_name;
            if (component.types.includes('administrative_area_level_1')) state = component.short_name;
            if (component.types.includes('country')) country = component.long_name;
            if (component.types.includes('postal_code')) postalCode = component.long_name;
          });

          const sublocalityLevel1 = addressComponents.find(c =>
            c.types.includes("sublocality_level_1")
          );
          const area = sublocalityLevel1 ? sublocalityLevel1.long_name : "";

          // Update DOM
          document.getElementById("address").textContent = data.results[0].formatted_address;
          document.getElementById("city").textContent = city;
          document.getElementById("state").textContent = state;
          document.getElementById("country").textContent = country;
          document.getElementById("zipCode").textContent = postalCode;
          document.getElementById("area").textContent = area;
        } else {
          console.error('No address found:', data);
          document.getElementById("address").textContent = "No address found";
        }
      } catch (error) {
        console.error('Error in fetching address:', error);
        document.getElementById("address").textContent = "Error fetching address";
      }
    }

    document.getElementById("fetchBtn").addEventListener("click", () => {
      const address = document.getElementById("addressInput").value.trim();
      if (address) {
        fetchCoordinatesFromAddress(address);
      } else {
        alert("Please enter an address");
      }
    });
  </script>
</body>
</html>
