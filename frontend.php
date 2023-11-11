<!DOCTYPE html>
<html>

<head>
    <title>Your Page Title</title>
    <style>
    /* Loading Spinner Styles */
.spinner-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000; /* Ensure it's above other elements */
    visibility: hidden; /* Hidden by default */
}
.loading-text {
    color: transparent;
    background: linear-gradient(45deg, #FFDC71, #FF8534);
    -webkit-background-clip: text;
    background-clip: text;
    font-size: 20px; /* Adjust the font size as needed */
    font-weight: bold; /* Optional: for bold text */
    margin-top: 20px; /* Adjust the spacing between the spinner and the text */
}

.spinner-container {
    display: flex;
    flex-direction: column;
    align-items: center;
}
.spinner-text {
    margin-top: 10px;
    color: #3498db; /* Example color, change as needed */
    font-size: 18px;
    font-weight: bold;
}
.time-group {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
}

.time-field {
    width: 48%; /* Adjust the width to slightly less than 50% to account for any padding/margins */
    display: flex;
    flex-direction: column;
}

.time-field select {
    width: 100%; /* Ensure the select element takes the full width of its container */
}




.spinner {
    border: 5px solid #f3f3f3;
    border-top: 5px solid #3498db;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 2s linear infinite;
}
.hidden {
    display: none;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

        .container {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 5px;
        }

        .form-group select,
        .form-group input {
            margin-bottom: 15px;
        }

        .results-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .result-box {
            border: 1px solid orange;
            padding: 10px;
            width: 100%;
            max-width: 300px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .results-space {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-group">
            <label for="location">Location:</label>
            <select id="location" name="location" required>
            <option value="6">Ahobilam </option>
            <option value="25">Amaravathi</option>
            <option value="13">Araku - Mayuri Hotel</option>
            <option value="14">Ananthagiri</option>
            <option value="15">Tyda Jungel Bells hotel</option>
            <option value="16">Araku Hill Resort</option>
            <option value="23">Dindi - Coconut Country Hotel</option>
            <option value="24">Dwaraka - Tirumala Hotel</option>
            <option value="38">Ettipothala</option>
            <option value="40">Gandikota</option>
            <option value="7">Gandikshethram</option>
            <option value="28">Horsely hills</option>
            <option value="41">Idupulapaya</option>
            <option value="3">Kadapa</option>
            <option value="31">Kailasnathkona</option>
            <option value="35">Sri Kalahasti</option>
            <option value="1">Kurnool</option>
            <option value="19">Lambasingi</option>
            <option value="10">Lepakshi</option>
            <option value="5">Mahanandi</option>
            <option value="36">Mypadu - Beach Resort</option>
            <option value="26">Nagarjunasagar Vijayapurisouth</option>
            <option value="34">Nellore</option>
            <option value="42">Votimitta</option>
            <option value="8">Orvakallu</option>
            <option value="48">Pulugudu</option>
            <option value="37">Srisailam</option>
            <option value="22">Suryalanka - Beach Resort</option>
            <option value="45">Tada - Flamingo Resort</option>
            <option value="20">Vijayawada - Bhavani Island</option>
            <option value="21">Vijayawada - Berm Park Hotel</option>
            <option value="11">Visakhapatnam - Yatrinivas Hotel</option>
                <!-- Location options here -->
            </select>
        </div>
        <div class="form-group">
            <label for="checkin">Check-in Date:</label>
            <input type="date" id="checkin" name="checkin" required>
        </div>
        <div class="form-group">
            <label for="checkout">Check-out Date:</label>
            <input type="date" id="checkout" name="checkout" required>
        </div>
  <div class="form-group">
    <label for="checkin-time">Check-in Time:</label>
    <select id="checkin-time" name="checkin-time" required></select>
</div>
<div class="form-group">
    <label for="checkout-time">Check-out Time:</label>
    <input type="text" id="checkout-time" name="checkout-time" readonly required>
</div>



        <button id="portfolio-posts-btn">Check Availability</button>
    </div>
    <div class="results-space"></div>
    <div class="results-container" id="portfolio-posts-container"></div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    var locationSelect = document.getElementById("location");
    var checkinTimeSelect = document.getElementById("checkin-time");
    var checkoutTimeInput = document.getElementById("checkout-time");

    // Populate check-in time options
    populateCheckinTimeOptions();

    // Event listeners
    locationSelect.addEventListener('change', handleLocationChange);
    checkinTimeSelect.addEventListener('change', updateCheckoutTime); // Added event listener for check-in time

    function populateCheckinTimeOptions() {
        for (var hour = 0; hour < 24; hour++) {
            var option = document.createElement("option");
            option.value = hour < 10 ? '0' + hour + ':00' : hour + ':00';
            option.textContent = option.value;
            checkinTimeSelect.appendChild(option);
        }
    }

    function handleLocationChange() {
        var selectedLocation = locationSelect.value;
        var checkinTime, checkoutTime;

        // Define location groups
        var group1 = ['6', '13', '14', '15', '16', '23', '38', '19', '36', '26', '22', '20', '21', '11'];
        var group2 = ['24', '28', '37'];
        var group3 = ['25', '40', '7', '41', '3', '31', '35', '1', '10', '5', '34', '42', '45', '48', '8'];

        if (group1.includes(selectedLocation)) {
            checkinTime = '10:00';
            checkoutTime = '09:00';
        } else if (group2.includes(selectedLocation)) {
            checkinTime = '12:00';
            checkoutTime = '11:00';
        } else if (group3.includes(selectedLocation)) {
            checkinTime = checkinTimeSelect.value;
            updateCheckoutTime(); // Call updateCheckoutTime to set the checkout time
        }

        // Set and disable/enable fields
        checkinTimeSelect.value = checkinTime;
        checkinTimeSelect.disabled = group1.includes(selectedLocation) || group2.includes(selectedLocation);
        checkoutTimeInput.value = checkoutTime;
    }

    function updateCheckoutTime() {
        var checkinTime = checkinTimeSelect.value;
        var checkoutHour = (parseInt(checkinTime.split(':')[0]) + 23) % 24;
        checkoutTimeInput.value = (checkoutHour < 10 ? '0' + checkoutHour : checkoutHour) + ':00';
    }
    // Call once on load
    handleLocationChange();
});
document.addEventListener("DOMContentLoaded", function () {
    var checkinTimeSelect = document.getElementById("checkin-time");
    var checkoutTimeInput = document.getElementById("checkout-time");
    var locationSelect = document.getElementById("location");
    var checkinDateInput = document.getElementById("checkin");
    var checkoutDateInput = document.getElementById("checkout");

    // Set default dates
    setDefaultDates();

    // Event listeners
    checkinDateInput.addEventListener('change', updateCheckoutDate);
    document.getElementById("portfolio-posts-btn").addEventListener("click", fetchHotelData);

    function setDefaultDates() {
        var today = new Date();
        checkinDateInput.valueAsDate = today;
        var tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        checkoutDateInput.valueAsDate = tomorrow;
    }

    function updateCheckoutDate() {
        var checkinDate = new Date(checkinDateInput.value);
        var checkoutDate = new Date(checkinDate);
        checkoutDate.setDate(checkoutDate.getDate() + 1);
        checkoutDateInput.valueAsDate = checkoutDate;
    }

   function fetchHotelData() {
    var selectedLocation = document.getElementById("location").value;
    var checkin = checkinDateInput.value;
    var checkout = checkoutDateInput.value;
    var checkinTime = checkinTimeSelect.value.replace(':', ''); // Format time for URL
    var checkoutTime = checkoutTimeInput.value.replace(':', ''); // Format time for URL

    var url = `https://staging.aptourismhotels.in/details3.php?hotelId=${selectedLocation}&checkInDate=${checkin}&checkOutDate=${checkout}&checkInTime=${checkinTime}&checkOutTime=${checkoutTime}`;

    var ourRequest = new XMLHttpRequest();
    ourRequest.open("GET", url);
    ourRequest.onload = function () {
        if (ourRequest.status >= 200 && ourRequest.status < 400) {
            var data = JSON.parse(ourRequest.responseText);
            if (Array.isArray(data)) {
                createHTML(data);
            } else {
                displayError(data.error);
            }
        } else {
            console.log("Error: " + ourRequest.status);
        }
    };
    ourRequest.onerror = function () {
        console.log("Connection error");
    };
    ourRequest.send();
}


function displayError(errorMessage) {
    var resultContainer = document.getElementById("portfolio-posts-container");
    resultContainer.innerHTML = `<div class="error-message"><strong>Status:</strong> ${errorMessage}</div>`;
}

function createHTML(data) {
    var resultContainer = document.getElementById("portfolio-posts-container");
    resultContainer.innerHTML = "";

    if (data.error) {
        var errorMessage = document.createElement("div");
        errorMessage.className = "error-message";
        errorMessage.innerHTML = `<strong>Status:</strong> ${data.error}`;
        resultContainer.appendChild(errorMessage);
    } else {
        data.forEach(function (item) {
            var resultBox = document.createElement("div");
            resultBox.className = "result-box";
            var roomOptions = '<option value="0">0</option>';
            for (var i = 1; i <= item.available_rooms; i++) {
                roomOptions += `<option value="${i}">${i}</option>`;
            }

            // Check if hotel name contains "ANANTHAGIRI" and room type is "NON A/C DORMITORY"
            var maxPerRoomText = "ADULT - 2 | CHILD - 2";
            if (item.hotel_name.includes("ANANTHAGIRI") && item.room_type_name === "NON A/C DORMITORY") {
                maxPerRoomText = "10 ADULTS, 0 CHILDS";
            }

            resultBox.innerHTML = `
                <strong>Hotel Name:</strong> ${item.hotel_name}<br>
                <strong>Room Type:</strong> ${item.room_type_name}<br>
                <strong>Max Per Room:</strong> ${maxPerRoomText}<br>
                <strong>Price Per Room:</strong> ${parseFloat(item.gross_amount)}<br>
                <strong>Tax GST:</strong> <span class="tax-gst">${parseFloat(item.tax)}</span><br>
                <strong>Required Rooms:</strong> <select class="rooms-dropdown">${roomOptions}</select><br>
                <strong>Total Price:</strong> <span class="total-price">0</span><br>
                <button class="add-to-cart-btn">Add to Cart</button>
                <button class="view-cart-btn hidden" onclick="window.location.href='https://aptourismhotels.in/wp/hotelcart';">View Cart</button>
            `;
            resultContainer.appendChild(resultBox);
                var roomsDropdown = resultBox.querySelector(".rooms-dropdown");
                var totalPriceSpan = resultBox.querySelector(".total-price");
                var addToCartBtn = resultBox.querySelector(".add-to-cart-btn");

                roomsDropdown.addEventListener("change", function () {
                    var selectedRooms = parseInt(this.value);
                    var pricePerRoom = parseFloat(item.gross_amount);
                    var taxPerRoom = parseFloat(item.tax);
                    var newTotalPrice = selectedRooms * (pricePerRoom + taxPerRoom);
                    totalPriceSpan.textContent = newTotalPrice.toFixed(2);
                    addToCartBtn.disabled = selectedRooms === 0;
                });

                handleAddToCartClick(addToCartBtn, item, totalPriceSpan, roomsDropdown, checkinDateInput, checkoutDateInput, checkinTimeSelect, checkoutTimeInput);
            });
        }
    }

    function handleAddToCartClick(button, item, totalPriceSpan, roomsDropdown, checkinDateInput, checkoutDateInput, checkinTimeSelect, checkoutTimeInput) {
    button.addEventListener("click", function (e) {
            e.preventDefault();

            var totalPrice = parseFloat(totalPriceSpan.textContent);
            if (totalPrice === 0) {
                alert('Please select the number of rooms before adding to cart.');
                return;
            }

            var hotelName = item.hotel_name;
            var roomType = item.room_type_name;
            var pricePerRoom = item.gross_amount;
            var taxGst = item.tax;
            var numberOfRooms = roomsDropdown.value;
            var checkinDate = checkinDateInput.value;
            var checkoutDate = checkoutDateInput.value;
            var checkinTime = checkinTimeSelect.value;
            var checkoutTime = checkoutTimeInput.value;
              // Generate a unique identifier for the cart item
            var uniqueId = roomType + "_" + checkinDate + "_" + checkoutDate + "_" + checkinTime + "_" + checkoutTime;
            // Generate a unique identifier for the cart item


            showSpinner('Adding to Cart');

            var xhr = new XMLHttpRequest();
             xhr.open('GET', `https://aptourismhotels.in/wp/cart/?add-to-cart=2829&hotel_fare=${totalPrice}&hotel_name=${encodeURIComponent(hotelName)}&room_type=${encodeURIComponent(roomType)}&price_per_room=${pricePerRoom}&tax_gst=${taxGst}&number_of_rooms=${numberOfRooms}&checkin_date=${checkinDate}&checkout_date=${checkoutDate}&checkin_time=${checkinTime}&checkout_time=${checkoutTime}&unique_id=${encodeURIComponent(uniqueId)}`, true);

            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4) {
                    hideSpinner();

                    if (xhr.status == 200) {
                        alert('Hotel Room Added to Cart Successfully!');
                        button.nextElementSibling.classList.remove('hidden');
                    } else {
                        alert('Error adding to cart. Please try again.');
                    }
                }
            };
            xhr.onerror = function () {
                hideSpinner();
                alert('Network error. Please check your connection.');
            };
            xhr.send();
        });
    }
document.getElementById("portfolio-posts-btn").addEventListener("click", function() {
    showSpinner('Checking Availability');
    // Fetch hotel data or perform other actions here
    // ...
    setTimeout(hideSpinner, 3000); // Hide after 3 seconds
});

    function showSpinner(message) {
    var loadingTextElement = document.querySelector('.loading-text');
    loadingTextElement.innerHTML = message; // Set custom message
    document.querySelector('.spinner-overlay').style.visibility = 'visible';
    loadingTextElement.style.display = 'block'; // Show the text
}

function hideSpinner() {
    document.querySelector('.spinner-overlay').style.visibility = 'hidden';
    document.querySelector('.loading-text').style.display = 'none'; // Hide the text
}


document.getElementById("portfolio-posts-btn").addEventListener("click", function() {
    showSpinner('Checking Availability');
    fetchHotelData();
    // The setTimeout call for hideSpinner should be inside fetchHotelData or its callback
});

});
</script>


<div class="spinner-overlay">
    <div class="spinner"></div>
    <div class="loading-text">Checking Availability</div>
</div>



</body>

</html>
