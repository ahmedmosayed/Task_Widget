@echo off
REM Task Widget - Simple Testing Guide

echo.
echo ===============================================
echo Task Widget - Complete Testing Checklist
echo ===============================================
echo.

echo PREREQUISITE: Make sure Laravel server is running
echo Command: php artisan serve
echo.

echo ===============================================
echo STEP 1: Register a Test Account
echo ===============================================
echo URL: http://localhost:8000/register
echo Email: test@example.com
echo Password: password123
echo.

echo ===============================================
echo STEP 2: Go to Dashboard
echo ===============================================
echo URL: http://localhost:8000/dashboard
echo You should see: "My Tasks" widget
echo.

echo ===============================================
echo STEP 3: Add a Task
echo ===============================================
echo 1. Type "Buy milk" in the input field
echo 2. Click "Add" button or press Enter
echo Expected: Task appears in the list immediately
echo.

echo ===============================================
echo STEP 4: Verify AJAX (Open DevTools F12)
echo ===============================================
echo 1. Open Network tab
echo 2. Add another task
echo 3. Look for: POST /api/tasks (Status 200)
echo.

echo ===============================================
echo STEP 5: Toggle Task Completion
echo ===============================================
echo 1. Click the checkbox next to "Buy milk"
echo Expected:
echo   - Checkbox is checked
echo   - Text becomes gray
echo   - Line-through appears
echo 2. Look in Network tab: PATCH /api/tasks/{id}/toggle
echo.

echo ===============================================
echo STEP 6: Delete a Task
echo ===============================================
echo 1. Hover over a task (Delete button appears)
echo 2. Click Delete
echo 3. Confirm deletion
echo Expected: Task disappears from list
echo 4. Look in Network tab: DELETE /api/tasks/{id}
echo.

echo ===============================================
echo STEP 7: Test Multi-User Isolation
echo ===============================================
echo 1. Open Private/Incognito Window
echo 2. Register as: test2@example.com
echo 3. Add tasks as User 2
echo 4. Logout and login as User 1
echo Expected: User 1 only sees their tasks
echo.

echo ===============================================
echo STEP 8: Database Verification
echo ===============================================
echo Command: php artisan tinker
echo Command: Task::all();
echo Expected: See all tasks in database
echo.

echo ===============================================
echo If all above tests pass:
echo ===============================================
echo YES - Everything is working 100%%
echo.

echo ===============================================
echo Troubleshooting:
echo ===============================================
echo 1. Tasks don't load?
echo    - Check F12 Console for errors
echo    - Check Network tab for API errors
echo.
echo 2. AJAX calls failing?
echo    - Make sure you're logged in
echo    - Check auth token in meta tag
echo.
echo 3. Database issues?
echo    - Run: php artisan migrate
echo    - Check: php artisan tinker
echo.

echo ===============================================
