document.addEventListener('DOMContentLoaded', function () {
    const taskInput = document.getElementById('task-input');
    const addTaskBtn = document.getElementById('add-task-btn');
    const taskList = document.getElementById('task-list');
    const API_URL = 'http://localhost/Modul2/tugas/to%20do%20list/api.php/tasks'; // Update to your backend URL

    // Fetch tasks from the database on page load
    fetchTasks();

    addTaskBtn.addEventListener('click', function () {
        const taskText = taskInput.value.trim();
        if (taskText !== '') {
            addTask(taskText);
            taskInput.value = '';
        }
    });

    function fetchTasks() {
        fetch(API_URL)
            .then(response => response.json())
            .then(tasks => {
                taskList.innerHTML = ''; // Clear existing tasks
                tasks.forEach(task => createTask(task.id, task.text, task.completed));
            })
            .catch(error => console.error('Error fetching tasks:', error));
    }

    function createTask(id, taskText, isCompleted = false) {
        const li = document.createElement('li');
        li.innerHTML = `
            <span class="task-text">${taskText}</span>
            <div class="task-buttons">
                <button class="edit-btn">✎</button>
                <button class="complete-btn">✔</button>
                <button class="delete-btn">✖</button>
            </div>
        `;
        if (isCompleted) li.classList.add('completed');
        li.dataset.id = id; // Store the task ID in the DOM

        taskList.appendChild(li);

        const completeBtn = li.querySelector('.complete-btn');
        const deleteBtn = li.querySelector('.delete-btn');
        const editBtn = li.querySelector('.edit-btn');
        const taskTextSpan = li.querySelector('.task-text');

        completeBtn.addEventListener('click', function () {
            const completed = !li.classList.contains('completed');
            updateTaskStatus(id, completed);
            li.classList.toggle('completed');
        });

        deleteBtn.addEventListener('click', function () {
            deleteTask(id);
            taskList.removeChild(li);
        });

        editBtn.addEventListener('click', function () {
            const newTaskText = prompt('Edit your task:', taskTextSpan.textContent);
            if (newTaskText && newTaskText.trim() !== '') {
                updateTaskText(id, newTaskText.trim());
                taskTextSpan.textContent = newTaskText.trim();
            }
        });
    }

    function addTask(taskText) {
        fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ text: taskText })
        })
            .then(response => response.json())
            .then(task => createTask(task.id, task.text, task.completed))
            .catch(error => console.error('Error adding task:', error));
    }

    function updateTaskStatus(id, completed) {
        fetch(`${API_URL}/${id}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ completed })
        })
            .catch(error => console.error('Error updating task status:', error));
    }

    function updateTaskText(id, newText) {
        fetch(`${API_URL}/${id}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ text: newText })
        })
            .catch(error => console.error('Error updating task text:', error));
    }

    function deleteTask(id) {
        fetch(`${API_URL}/${id}`, {
            method: 'DELETE'
        })
            .catch(error => console.error('Error deleting task:', error));
    }
});
