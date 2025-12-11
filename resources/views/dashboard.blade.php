<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border border-blue-100">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-8">
                    <h2 class="text-3xl font-bold text-white">✨ My Tasks</h2>
                    <p class="text-blue-100 mt-1">Stay organized and productive</p>
                </div>

                <div class="p-8">
                    <!-- My Tasks Widget -->
                    <div class="mb-8">
                        <!-- Add Task Form -->
                        <div class="mb-8 flex gap-2">
                            <input
                                type="text"
                                id="taskInput"
                                placeholder="What needs to be done?"
                                class="flex-1 px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors text-gray-700 placeholder-gray-400"
                            />
                            <button
                                id="addBtn"
                                class="px-8 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all font-semibold shadow-md hover:shadow-lg transform hover:scale-105"
                            >
                                ➕ Add
                            </button>
                        </div>

                        <!-- Task List -->
                        <div id="tasksList" class="space-y-3">
                            <div class="text-center text-gray-500 py-12">
                                <div class="inline-block p-4 bg-gray-100 rounded-full mb-2">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <p class="text-lg">Loading tasks...</p>
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div id="emptyState" class="text-center text-gray-500 py-12 hidden">
                            <div class="inline-block p-4 bg-blue-100 rounded-full mb-2">
                                <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m0 0h6"></path>
                                </svg>
                            </div>
                            <p class="text-lg font-medium">No tasks yet</p>
                            <p class="text-sm text-gray-400 mt-1">Create your first task to get started! 🚀</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const taskInput = document.getElementById('taskInput');
        const addBtn = document.getElementById('addBtn');
        const tasksList = document.getElementById('tasksList');
        const emptyState = document.getElementById('emptyState');

        // Get auth token from meta tag if available (optional).
        // If no token is present we rely on cookie-based (session) auth and send credentials.
        const getAuthToken = () => document.querySelector('meta[name="auth-token"]')?.content || null;

        const authHeaders = (withJson = false) => {
            const token = getAuthToken();
            const headers = { 'Accept': 'application/json' };
            if (withJson) headers['Content-Type'] = 'application/json';
            // include csrf token for session-based requests (prevents 419)
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
            if (csrf) headers['X-CSRF-TOKEN'] = csrf;
            if (token) headers['Authorization'] = 'Bearer ' + token;
            return headers;
        };

        function loadTasks() {
            fetch('/api/tasks', {
                credentials: 'same-origin',
                headers: authHeaders(false)
            })
            .then(async (response) => {
                if (!response.ok) {
                    let body = null;
                    try { body = await response.json(); } catch (e) { body = await response.text().catch(() => null); }
                    throw new Error(body?.message || `Status ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                renderTasks(data.data || []);
            })
            .catch(error => {
                console.error('Error loading tasks:', error);
                tasksList.innerHTML = '<div class="text-red-500">Error loading tasks</div>';
            });
        }

        // Render tasks on page
        function renderTasks(tasks) {
            if (tasks.length === 0) {
                tasksList.innerHTML = '';
                emptyState.classList.remove('hidden');
            } else {
                emptyState.classList.add('hidden');
                tasksList.innerHTML = tasks.map(task => `
                    <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg group hover:from-blue-100 hover:to-indigo-100 transition-all border border-blue-100 hover:border-blue-300 shadow-sm hover:shadow-md" data-task-id="${task.id}">
                        <input
                            type="checkbox"
                            ${task.completed ? 'checked' : ''}
                            class="task-checkbox w-6 h-6 text-blue-500 rounded cursor-pointer accent-blue-500"
                            data-task-id="${task.id}"
                        />
                        <span class="task-title flex-1 text-lg ${task.completed ? 'line-through text-gray-400' : 'text-gray-700'} transition-all duration-300">
                            ${escapeHtml(task.title)}
                        </span>
                        <div class="flex gap-2">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full ${task.completed ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'}">
                                ${task.completed ? '✓ Done' : '⏳ Pending'}
                            </span>
                            <button
                                class="delete-btn px-3 py-1.5 text-red-600 hover:bg-red-100 hover:text-red-700 rounded opacity-0 group-hover:opacity-100 transition-all text-sm font-medium"
                                data-task-id="${task.id}"
                            >
                                🗑️ Delete
                            </button>
                        </div>
                    </div>
                `).join('');

                // Attach event listeners
                document.querySelectorAll('.task-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', toggleTask);
                });

                document.querySelectorAll('.delete-btn').forEach(btn => {
                    btn.addEventListener('click', deleteTask);
                });
            }
        }

        // Add new task with improved error reporting
        function addTask() {
            const title = taskInput.value.trim();

            if (!title) {
                alert('Please enter a task title');
                return;
            }

            fetch('/api/tasks', {
                method: 'POST',
                credentials: 'same-origin',
                headers: authHeaders(true),
                body: JSON.stringify({ title })
            })
            .then(async (response) => {
                const ct = response.headers.get('content-type') || '';
                let body = null;
                try {
                    body = ct.includes('application/json') ? await response.json() : await response.text();
                } catch (e) {
                    body = null;
                }

                if (!response.ok) {
                    console.error('Create failed', response.status, body);
                    alert('Error creating task: ' + (body?.message || `Status ${response.status}`));
                    return;
                }

                // Success: clear input and reload tasks
                taskInput.value = '';
                loadTasks();
            })
            .catch(error => {
                console.error('Error adding task (network):', error);
                alert('Network error creating task');
            });
        }

        // Toggle task completion
        function toggleTask(e) {
            const taskId = e.target.dataset.taskId;
            const checked = e.target.checked;

            // Send a PUT to update is_completed via the existing update route
            fetch(`/api/tasks/${taskId}`, {
                method: 'PUT',
                credentials: 'same-origin',
                headers: authHeaders(true),
                body: JSON.stringify({ is_completed: checked })
            })
            .then(async (response) => {
                const ct = response.headers.get('content-type') || '';
                let body = null;
                try {
                    body = ct.includes('application/json') ? await response.json() : await response.text();
                } catch (e) {
                    body = null;
                }

                if (!response.ok) {
                    console.error('Update failed', response.status, body);
                    alert('Error updating task: ' + (body?.message || `Status ${response.status}`));
                    // revert checkbox state and reload list
                    e.target.checked = !checked;
                    loadTasks();
                    return;
                }

                if (body && body.data) {
                    const taskElement = document.querySelector(`[data-task-id="${taskId}"]`);
                    const titleSpan = taskElement.querySelector('.task-title');
                    const statusTag = taskElement.querySelector('span.px-3');

                    const isCompleted = body.data.completed;

                    // Update checkbox (ensure consistent)
                    const cb = taskElement.querySelector('.task-checkbox');
                    if (cb) cb.checked = isCompleted;

                    // Update title styling
                    if (isCompleted) {
                        titleSpan.classList.add('line-through', 'text-gray-400');
                    } else {
                        titleSpan.classList.remove('line-through', 'text-gray-400');
                    }

                    // Update status tag
                    if (statusTag) {
                        if (isCompleted) {
                            statusTag.className = 'px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700';
                            statusTag.textContent = '✓ Done';
                        } else {
                            statusTag.className = 'px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700';
                            statusTag.textContent = '⏳ Pending';
                        }
                    }
                } else {
                    loadTasks();
                }
            })
            .catch(error => {
                console.error('Error updating task (network):', error);
                e.target.checked = !checked;
                loadTasks(); // Reload on error
            });
        }

        // Delete task
        function deleteTask(e) {
            const taskId = e.target.dataset.taskId;

            if (!confirm('Are you sure you want to delete this task?')) {
                return;
            }

            fetch(`/api/tasks/${taskId}`, {
                method: 'DELETE',
                credentials: 'same-origin',
                headers: authHeaders(false)
            })
            .then(async (response) => {
                if (response.ok) {
                    loadTasks();
                    return;
                }
                let body = null;
                try { body = await response.json(); } catch (e) { body = await response.text().catch(() => null); }
                console.error('Delete failed', response.status, body);
                alert('Error deleting task: ' + (body?.message || `Status ${response.status}`));
            })
            .catch(error => {
                console.error('Error deleting task (network):', error);
                alert('Error deleting task');
            });
        }

        // Escape HTML to prevent XSS
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Event listeners
        addBtn.addEventListener('click', addTask);
        taskInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') addTask();
        });

        // Ensure CSRF cookie for cookie-based auth (sanctum) if no bearer token,
        // then load tasks. This prevents 419 responses when using session auth.
        async function ensureCsrfAndLoad() {
            const token = getAuthToken();
            if (!token) {
                try {
                    // Request CSRF cookie from sanctum (sets XSRF-TOKEN cookie)
                    await fetch('/sanctum/csrf-cookie', { credentials: 'same-origin' });
                } catch (e) {
                    console.warn('Could not fetch CSRF cookie:', e);
                }
            }
            loadTasks();
        }

        // Load tasks on page load (with CSRF handshake if needed)
        ensureCsrfAndLoad();
    </script>

    <style>
        .line-through {
            text-decoration: line-through;
        }
    </style>
</x-app-layout>
