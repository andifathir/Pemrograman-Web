document.addEventListener('DOMContentLoaded', function () {
    const taskInput = document.getElementById('task-input');
    const addTaskBtn = document.getElementById('add-task-btn');
    const taskList = document.getElementById('task-list');

    // Load saved tasks from localStorage
    const savedTasks = JSON.parse(localStorage.getItem('tasks')) || [];
    savedTasks.forEach(task => createTask(task.text, task.completed));

    addTaskBtn.addEventListener('click', function () {
        const taskText = taskInput.value.trim();
        if (taskText !== '') {
            createTask(taskText);
            saveTask(taskText);
            taskInput.value = '';
        }
    });

    function createTask(taskText, isCompleted = false) {
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
    
        taskList.appendChild(li);
    
        const completeBtn = li.querySelector('.complete-btn');
        const deleteBtn = li.querySelector('.delete-btn');
        const editBtn = li.querySelector('.edit-btn');
        const taskTextSpan = li.querySelector('.task-text');
    
        completeBtn.addEventListener('click', function () {
            li.classList.toggle('completed');
            updateTaskStatus(taskTextSpan.textContent);
        });
    
        deleteBtn.addEventListener('click', function () {
            taskList.removeChild(li);
            removeTask(taskTextSpan.textContent);
        });
    
        editBtn.addEventListener('click', function () {
            const newTaskText = prompt('Edit your task:', taskTextSpan.textContent);
            if (newTaskText && newTaskText.trim() !== '') {
                updateTaskText(taskTextSpan.textContent, newTaskText.trim());
                taskTextSpan.textContent = newTaskText.trim();
            }
        });
    }

    // function createTask(taskText, isCompleted = false) {
    //     const li = document.createElement('li');
    //     li.innerHTML = `
    //         <span class="task-text">${taskText}</span>
    //         <div>
    //             <button class="edit-btn">✎</button>
    //             <button class="complete-btn">✔</button>
    //             <button class="delete-btn">✖</button>
    //         </div>
    //     `;
    //     if (isCompleted) li.classList.add('completed');

    //     taskList.appendChild(li);

    //     const completeBtn = li.querySelector('.complete-btn');
    //     const deleteBtn = li.querySelector('.delete-btn');
    //     const editBtn = li.querySelector('.edit-btn');
    //     const taskTextSpan = li.querySelector('.task-text');

    //     completeBtn.addEventListener('click', function () {
    //         li.classList.toggle('completed');
    //         updateTaskStatus(taskTextSpan.textContent);
    //     });

    //     deleteBtn.addEventListener('click', function () {
    //         taskList.removeChild(li);
    //         removeTask(taskTextSpan.textContent);
    //     });

    //     editBtn.addEventListener('click', function () {
    //         const newTaskText = prompt('Edit your task:', taskTextSpan.textContent);
    //         if (newTaskText && newTaskText.trim() !== '') {
    //             updateTaskText(taskTextSpan.textContent, newTaskText.trim());
    //             taskTextSpan.textContent = newTaskText.trim();
    //         }
    //     });
    // }

    function saveTask(taskText) {
        const tasks = JSON.parse(localStorage.getItem('tasks')) || [];
        tasks.push({ text: taskText, completed: false });
        localStorage.setItem('tasks', JSON.stringify(tasks));
    }

    function removeTask(taskText) {
        let tasks = JSON.parse(localStorage.getItem('tasks')) || [];
        tasks = tasks.filter(task => task.text !== taskText);
        localStorage.setItem('tasks', JSON.stringify(tasks));
    }

    function updateTaskStatus(taskText) {
        const tasks = JSON.parse(localStorage.getItem('tasks')) || [];
        tasks.forEach(task => {
            if (task.text === taskText) {
                task.completed = !task.completed;
            }
        });
        localStorage.setItem('tasks', JSON.stringify(tasks));
    }

    function updateTaskText(oldText, newText) {
        const tasks = JSON.parse(localStorage.getItem('tasks')) || [];
        tasks.forEach(task => {
            if (task.text === oldText) {
                task.text = newText;
            }
        });
        localStorage.setItem('tasks', JSON.stringify(tasks));
    }
});
