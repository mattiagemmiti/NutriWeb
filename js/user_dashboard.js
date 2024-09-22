// Main script for handling user dashboard interactions, including fetching and displaying food logs, feedback, 
// and submitting new food entries

// Initializes the data fetching processes as soon as the DOM content is fully loaded.
document.addEventListener('DOMContentLoaded', function () {
    fetchFoodLogs();  // load foods when page is loaded
    fetchFeedback();  // loads feedbacks
});

// Prevents default form submission to handle data sending via AJAX for better user experience
document.getElementById('foodForm').addEventListener('submit', function (e) {
    e.preventDefault(); 

    // Collects and calculates the food data based on user input before sending to the server

    const formData = new FormData(this);

    // Maths based on portion
    const portionSize = formData.get('portionSize');
    const actualCalories = (formData.get('calories') * portionSize) / 100;
    const actualCarbs = (formData.get('carbs') * portionSize) / 100;
    const actualProteins = (formData.get('proteins') * portionSize) / 100;
    const actualFats = (formData.get('fats') * portionSize) / 100;
    const actualFiber = (formData.get('fiber') * portionSize) / 100;

    // Sends the calculated food data to the server and handles the response
    fetch('http://localhost/NutriWebLocal/php/log_food.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {

        console.log(data);  // Dev Option for console Debugging

        // On successful data submission, updates the UI to reflect the new food entry and recalculates the daily totals.
        if(data.includes("Food log added successfully")) {
            const foodData = {
                date: new Date().toISOString().split('T')[0], // Current date
                food: formData.get('foodName'),
                portion: portionSize,
                calories: actualCalories,
                carbs: actualCarbs,
                proteins: actualProteins,
                fats: actualFats,
                fiber: actualFiber
            };
            addFoodToList(foodData);  // Call function to add food to list
            updateDailyTotals(foodData);  // Update daily data
        }

        // Resets the form fields to clear after successful submission.
        document.getElementById('foodForm').reset();
    })
    .catch(error => console.error('Error:', error)); //errors handling console debugging
});

// Fetches the user's food logs from the server and processes them for display
function fetchFoodLogs() {

    // Organizes food data into daily totals and updates the UI accordingly.

    fetch('http://localhost/NutriWebLocal/php/fetch_food_logs.php')
    .then(response => response.json())
    .then(foods => {
        if (foods.length > 0) {
            const dailyTotals = {};

            foods.forEach(food => {
                addFoodToList(food);  // Add each food to the table

                // Sum daily nutrients

                if (!dailyTotals[food.date]) {
                    dailyTotals[food.date] = {
                        calories: 0, carbs: 0, proteins: 0, fats: 0, fiber: 0
                    };
                }

                dailyTotals[food.date].calories += parseFloat(food.calories);
                dailyTotals[food.date].carbs += parseFloat(food.carbs);
                dailyTotals[food.date].proteins += parseFloat(food.proteins);
                dailyTotals[food.date].fats += parseFloat(food.fats);
                dailyTotals[food.date].fiber += parseFloat(food.fiber);
            });

            // Update table
            for (const date in dailyTotals) {
                updateDailyTotals({date: date, ...dailyTotals[date]});
            }
        } else {
            console.log('No food logs found.'); //debug
        }
    })
    .catch(error => {
        console.error('Error fetching food logs:', error); //debug
    });
}


// Creates and appends food entries to the food log table in the user dashboard
function addFoodToList(food) {
    const tbody = document.getElementById('foodTableBody');

    // Add food row
    let foodRow = document.createElement('tr');
    foodRow.innerHTML = `
        <td>${food.date}</td>
        <td>${food.food}</td>
        <td>${food.portion}</td>
        <td>${food.calories.toFixed(2)}</td>
        <td>${food.carbs.toFixed(2)}</td>
        <td>${food.proteins.toFixed(2)}</td>
        <td>${food.fats.toFixed(2)}</td>
        <td>${food.fiber.toFixed(2)}</td>`;
    tbody.appendChild(foodRow);
}

// Checks if a total row for the current date exists and updates it; otherwise, creates a new total row.
function updateDailyTotals(food) {
    const tbody = document.getElementById('totalsTableBody');
    let totalRow = document.querySelector(`#totalsTableBody tr[data-date="${food.date}"]`);

    if (totalRow) {
        // Fetch existing row values
        const currentCalories = parseFloat(totalRow.querySelector('.total-calories').textContent);
        const currentCarbs = parseFloat(totalRow.querySelector('.total-carbs').textContent);
        const currentProteins = parseFloat(totalRow.querySelector('.total-proteins').textContent);
        const currentFats = parseFloat(totalRow.querySelector('.total-fats').textContent);
        const currentFiber = parseFloat(totalRow.querySelector('.total-fiber').textContent);

        // Update it with current values
        totalRow.innerHTML = `
            <td>${food.date}</td>
            <td class="total-calories">${(currentCalories + parseFloat(food.calories)).toFixed(2)}</td>
            <td class="total-carbs">${(currentCarbs + parseFloat(food.carbs)).toFixed(2)}</td>
            <td class="total-proteins">${(currentProteins + parseFloat(food.proteins)).toFixed(2)}</td>
            <td class="total-fats">${(currentFats + parseFloat(food.fats)).toFixed(2)}</td>
            <td class="total-fiber">${(currentFiber + parseFloat(food.fiber)).toFixed(2)}</td>`;
    } else {
        // Create new row
        totalRow = document.createElement('tr');
        totalRow.setAttribute('data-date', food.date);
        totalRow.innerHTML = `
            <td>${food.date}</td>
            <td class="total-calories">${food.calories.toFixed(2)}</td>
            <td class="total-carbs">${food.carbs.toFixed(2)}</td>
            <td class="total-proteins">${food.proteins.toFixed(2)}</td>
            <td class="total-fats">${food.fats.toFixed(2)}</td>
            <td class="total-fiber">${food.fiber.toFixed(2)}</td>`;
        tbody.appendChild(totalRow);
    }
}

// Fetches and displays feedback messages from the nutritionist to the user.
function fetchFeedback() {
    fetch('http://localhost/NutriWebLocal/php/fetch_feedback.php')
    .then(response => response.json())
    .then(feedbacks => {
        console.log('Fetched feedbacks:', feedbacks);  // debug

        // Iterates through each feedback message and appends it to the feedback list in the UI.
        if (Array.isArray(feedbacks)) {
            const feedbackList = document.getElementById('feedbackList');
            feedbackList.innerHTML = '';  // Clean Current list
            feedbacks.forEach(feedback => {
                let feedbackItem = document.createElement('li');
                feedbackItem.className = 'list-group-item';
                feedbackItem.textContent = `${feedback.date}: ${feedback.message}`;
                feedbackList.appendChild(feedbackItem);
            });
        } else {
            console.error('Unexpected response format:', feedbacks); // debug
        }
    })
    .catch(error => console.error('Error fetching feedback:', error)); // error handling debug
}
