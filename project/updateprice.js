// updateprice.js
document.addEventListener("DOMContentLoaded", function () {
    const quantityInputs = document.querySelectorAll(".quantity-input");
    const cartForm = document.getElementById("cart-form");
    const totalPriceElement = document.getElementById("total-price");

    function updatePrices() {
        let totalPrice = 0;

        document.querySelectorAll(".cart-item").forEach(function (itemRow) {
            const pricePerUnit = parseFloat(itemRow.getAttribute("data-price"));
            const quantityInput = itemRow.querySelector(".quantity-input");
            const itemPriceElement = itemRow.querySelector(".item-price");

            const itemQuantity = parseInt(quantityInput.value);
            const itemTotalPrice = pricePerUnit * itemQuantity;
            itemPriceElement.textContent = itemTotalPrice.toFixed(2);

            totalPrice += itemTotalPrice;
        });

        totalPriceElement.textContent = totalPrice.toFixed(2);
    }

    quantityInputs.forEach(input => {
        input.addEventListener("change", function () {
            cartForm.submit(); // Submit form on quantity change
        });
    });
});

// JavaScript function to toggle payment fields
function togglePaymentFields() {
    var paymentMethod = document.getElementById("payment").value;
    var creditCardFields = document.getElementById("credit-card-fields");
    var paypalFields = document.getElementById("paypal-fields");

    if (paymentMethod === "paypal") {
        creditCardFields.style.display = "none";
        paypalFields.style.display = "block";
    } else {
        creditCardFields.style.display = "block";
        paypalFields.style.display = "none";
    }
}

// Call the function once to set initial state based on the selected payment method
window.onload = function() {
    togglePaymentFields();  // Set the state on page load
}

// Format card number to add spaces every 4 digits
function formatCardNumber() {
    var cardNumber = document.getElementById("card_number");
    var formattedCardNumber = cardNumber.value.replace(/\D/g, "").replace(/(\d{4})(?=\d)/g, "$1 ");
    cardNumber.value = formattedCardNumber;
}

// Format expiry date to MM/YY and validate month (01-12)
function formatExpiryDate() {
    var expiryDate = document.getElementById("expiry_date");
    var inputValue = expiryDate.value.replace(/\D/g, ''); // Remove any non-numeric characters

    // Limit to 4 digits max
    inputValue = inputValue.substring(0, 4);

    // Insert slash after first two digits (MM/YY)
    if (inputValue.length > 2) {
        inputValue = inputValue.substring(0, 2) + '/' + inputValue.substring(2, 4);
    }

    // Check if the month part is valid (01 - 12)
    var month = inputValue.substring(0, 2);
    if (month !== '' && (parseInt(month) < 1 || parseInt(month) > 12)) {
        expiryDate.setCustomValidity("Please enter a valid month (01-12)"); // Set error message if month is invalid
    } else {
        expiryDate.setCustomValidity(""); // Clear error message if month is valid
    }

    expiryDate.value = inputValue; // Update the input field with the formatted value
}

// Limit input to numeric characters for card number, CVV, etc.
document.getElementById("cvv").addEventListener("input", function (e) {
    this.value = this.value.replace(/\D/g, ''); // Only numbers allowed
});

document.getElementById("card_number").addEventListener("input", function (e) {
    this.value = this.value.replace(/\D/g, ''); // Only numbers allowed
});