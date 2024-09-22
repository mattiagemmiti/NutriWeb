// Main event listener that triggers when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {
     // Fetches and displays the list of users from the backend on page load
    function fetchUserList() {
        fetch('http://localhost/NutriWebLocal/php/fetch_users.php')
            .then(response => response.json())
            .then(users => populateUserList(users))
            .catch(error => console.error('Error fetching users:', error));
    }

    // Populates the user list in the html UI
    function populateUserList(users) {
        const userList = document.getElementById('userList');
        userList.innerHTML = '';  // Clear any previous entries
        users.forEach(user => {
            let userLink = document.createElement('a');
            userLink.href = '#';
            userLink.className = 'list-group-item list-group-item-action';
            userLink.textContent = user.username;
            userLink.dataset.userId = user.user_id; // Stores user ID in data attribute for later use
            console.log("usersidetc:", user.user_id, user.username);
            userLink.addEventListener('click', function() {
                fetchUserDetails(this.dataset.userId); // Fetches and displays user details when clicked
            });
            userList.appendChild(userLink);
        });
        
        console.log("Users Data:", users); // Dev.Option to check from console if data is fetched correctly 

    }

    // Fetches details for a specific user when a user link is clicked
    function fetchUserDetails(userId) {

        selectedUserId = userId;  // Stores the selected user ID globally for feedback use
        console.log('Fetching details for userId:', userId);  // Dev Console Check line
        fetch(`http://localhost/NutriWebLocal/php/fetch_user_details.php?user_id=${userId}`)
            .then(response => response.json())
            .then(details => displayUserDetails(details))
            .catch(error => console.error('Error fetching user details:', error));
    }


// Displays detailed information about a user, such as food logs, in a table.
function displayUserDetails(details) {
    const detailsContainer = document.getElementById('details-container');
    detailsContainer.innerHTML = '';  // Clear existing details

    if (details.length === 0) {
        detailsContainer.innerHTML = '<p>No details available for this user.</p>';
    } else {
        let table = document.createElement('table');
        table.className = 'table table-striped';  // Bootstrap table classes for styling

        // Create table header
        let thead = document.createElement('thead');
        thead.innerHTML = `
            <tr>
                <th>Date</th>
                <th>Food</th>
                <th>Portion (g)</th>
                <th>Calories</th>
                <th>Carbs (g)</th>
                <th>Proteins (g)</th>
                <th>Fats (g)</th>
                <th>Fiber (g)</th>
            </tr>`;
        table.appendChild(thead);

        // Create table body
        let tbody = document.createElement('tbody');
        let currentDay = null;
        let totals = {calories: 0, carbs: 0, proteins: 0, fats: 0, fiber: 0};

        // Processes each food entry and adds rows to the table
        details.forEach((detail, index) => {
            if (currentDay && detail.date !== currentDay) {
                // Adds a row for totals when the day changes
                let totalRow = document.createElement('tr');
                totalRow.innerHTML = `
                    <td colspan="3"><strong>Total for ${currentDay}</strong></td>
                    <td><strong>${totals.calories.toFixed(2)}</strong></td>
                    <td><strong>${totals.carbs.toFixed(2)}</strong></td>
                    <td><strong>${totals.proteins.toFixed(2)}</strong></td>
                    <td><strong>${totals.fats.toFixed(2)}</strong></td>
                    <td><strong>${totals.fiber.toFixed(2)}</strong></td>`;
                tbody.appendChild(totalRow);

                // Reset totals for the new day
                totals = {calories: 0, carbs: 0, proteins: 0, fats: 0, fiber: 0};
            }

            // Update current day
            currentDay = detail.date;

            // Accumulate totals
            totals.calories += parseFloat(detail.calories);
            totals.carbs += parseFloat(detail.carbs);
            totals.proteins += parseFloat(detail.proteins);
            totals.fats += parseFloat(detail.fats);
            totals.fiber += parseFloat(detail.fiber);

            // Add row for current detail
            let row = document.createElement('tr');
            row.innerHTML = `
                <td>${detail.date}</td>
                <td>${detail.food}</td>
                <td>${detail.portion}</td>
                <td>${detail.calories}</td>
                <td>${detail.carbs}</td>
                <td>${detail.proteins}</td>
                <td>${detail.fats}</td>
                <td>${detail.fiber}</td>`;
            tbody.appendChild(row);

            // If it's the last item, add the totals row
            if (index === details.length - 1) {
                let totalRow = document.createElement('tr');
                totalRow.innerHTML = `
                    <td colspan="3"><strong>Total for ${currentDay}</strong></td>
                    <td><strong>${totals.calories.toFixed(2)}</strong></td>
                    <td><strong>${totals.carbs.toFixed(2)}</strong></td>
                    <td><strong>${totals.proteins.toFixed(2)}</strong></td>
                    <td><strong>${totals.fats.toFixed(2)}</strong></td>
                    <td><strong>${totals.fiber.toFixed(2)}</strong></td>`;
                tbody.appendChild(totalRow);
            }
        });

        table.appendChild(tbody);
        detailsContainer.appendChild(table);
    }
}

    // Listen for the submit event on the feedback form to process and send feedback
    document.getElementById('feedbackForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevents the default form submission behavior
    
        const feedbackMessage = document.getElementById('feedbackMessage').value;

        // Checks if a feedback message has been written and a user has been selected
        if (feedbackMessage.trim() !== '' && selectedUserId) {
            fetch('http://localhost/NutriWebLocal/php/send_feedback.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ user_id: selectedUserId, message: feedbackMessage })
            })
            .then(response => response.text())
            .then(data => {
                console.log(data); //Logs the server response to the console
                document.getElementById('feedbackMessage').value = '';  // Clears the feedback message input after sending
                alert('Feedback sent successfully'); //Alerts the user that feedback has been successfully sent
            })
            .catch(error => console.error('Error sending feedback:', error)); //Handles errors in sending feedback and logs them
        } else {
            alert('Please select a user and write a message before sending.'); //Alerts the user to select a user and write a message before submitting
        }
    });
    
    //Adds click event listeners to each user list item for fetching detailed user information
    document.querySelectorAll('.user-list-item').forEach(item => {
        item.addEventListener('click', function() {
            const userId = this.dataset.userId;  //Retrieves the user ID stored in a data attribute
            fetchUserDetails(userId); //Calls the function to fetch and display user details based on the selected user ID
        });
    });




    fetchUserList();  // Initial fetch for the user list when the page loads
});
