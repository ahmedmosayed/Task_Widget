#!/usr/bin/env pwsh

# Task Widget - Automated Testing Script

Write-Host "======================================" -ForegroundColor Cyan
Write-Host "Task Widget - Automated Testing" -ForegroundColor Cyan
Write-Host "======================================" -ForegroundColor Cyan
Write-Host ""

$BaseUrl = "http://localhost:8000/api"
$TestEmail = "testuser$(Get-Random)@example.com"
$TestPassword = "password123"
$Token = ""

# Color output helper
function Write-Success {
    param([string]$Message)
    Write-Host "✅ $Message" -ForegroundColor Green
}

function Write-Error {
    param([string]$Message)
    Write-Host "❌ $Message" -ForegroundColor Red
}

function Write-Info {
    param([string]$Message)
    Write-Host "ℹ️  $Message" -ForegroundColor Blue
}

# Test 1: Register
Write-Host ""
Write-Info "TEST 1: Register User"
try {
    $response = Invoke-WebRequest -Uri "$BaseUrl/register" `
        -Method POST `
        -ContentType "application/json" `
        -Body @{
            name = "Test User"
            email = $TestEmail
            password = $TestPassword
            password_confirmation = $TestPassword
        } | ConvertTo-Json | % {
            try { ConvertFrom-Json $_ } catch { $_ }
        }

    $response = Invoke-WebRequest -Uri "$BaseUrl/register" `
        -Method POST `
        -ContentType "application/json" `
        -Body (ConvertTo-Json @{
            name = "Test User"
            email = $TestEmail
            password = $TestPassword
            password_confirmation = $TestPassword
        })

    $result = $response.Content | ConvertFrom-Json
    $Token = $result.access_token
    Write-Success "User registered with token: $(($Token.Substring(0, 20))...))"
} catch {
    Write-Error "Register failed: $_"
    exit 1
}

# Test 2: Create Task
Write-Host ""
Write-Info "TEST 2: Create Task"
try {
    $response = Invoke-WebRequest -Uri "$BaseUrl/tasks" `
        -Method POST `
        -ContentType "application/json" `
        -Headers @{
            "Authorization" = "Bearer $Token"
        } `
        -Body (ConvertTo-Json @{
            title = "Buy milk"
        })

    $result = $response.Content | ConvertFrom-Json
    $TaskId = $result.data.id
    Write-Success "Task created with ID: $TaskId"
} catch {
    Write-Error "Create task failed: $_"
}

# Test 3: Get Tasks
Write-Host ""
Write-Info "TEST 3: Get All Tasks"
try {
    $response = Invoke-WebRequest -Uri "$BaseUrl/tasks" `
        -Method GET `
        -ContentType "application/json" `
        -Headers @{
            "Authorization" = "Bearer $Token"
        }

    $result = $response.Content | ConvertFrom-Json
    $TaskCount = $result.data.Count
    Write-Success "Retrieved $TaskCount task(s)"
} catch {
    Write-Error "Get tasks failed: $_"
}

# Test 4: Get Single Task
Write-Host ""
Write-Info "TEST 4: Get Single Task"
try {
    $response = Invoke-WebRequest -Uri "$BaseUrl/tasks/$TaskId" `
        -Method GET `
        -ContentType "application/json" `
        -Headers @{
            "Authorization" = "Bearer $Token"
        }

    $result = $response.Content | ConvertFrom-Json
    Write-Success "Task retrieved: $($result.data.title)"
} catch {
    Write-Error "Get single task failed: $_"
}

# Test 5: Update Task
Write-Host ""
Write-Info "TEST 5: Update Task"
try {
    $response = Invoke-WebRequest -Uri "$BaseUrl/tasks/$TaskId" `
        -Method PUT `
        -ContentType "application/json" `
        -Headers @{
            "Authorization" = "Bearer $Token"
        } `
        -Body (ConvertTo-Json @{
            title = "Buy milk and bread"
        })

    $result = $response.Content | ConvertFrom-Json
    Write-Success "Task updated to: $($result.data.title)"
} catch {
    Write-Error "Update task failed: $_"
}

# Test 6: Toggle Task
Write-Host ""
Write-Info "TEST 6: Toggle Task Status"
try {
    $response = Invoke-WebRequest -Uri "$BaseUrl/tasks/$TaskId/toggle" `
        -Method PATCH `
        -ContentType "application/json" `
        -Headers @{
            "Authorization" = "Bearer $Token"
        }

    $result = $response.Content | ConvertFrom-Json
    Write-Success "Task toggled. Completed: $($result.data.completed)"
} catch {
    Write-Error "Toggle task failed: $_"
}

# Test 7: Delete Task
Write-Host ""
Write-Info "TEST 7: Delete Task"
try {
    $response = Invoke-WebRequest -Uri "$BaseUrl/tasks/$TaskId" `
        -Method DELETE `
        -ContentType "application/json" `
        -Headers @{
            "Authorization" = "Bearer $Token"
        }

    Write-Success "Task deleted successfully"
} catch {
    Write-Error "Delete task failed: $_"
}

# Test 8: Logout
Write-Host ""
Write-Info "TEST 8: Logout"
try {
    $response = Invoke-WebRequest -Uri "$BaseUrl/logout" `
        -Method POST `
        -ContentType "application/json" `
        -Headers @{
            "Authorization" = "Bearer $Token"
        }

    $result = $response.Content | ConvertFrom-Json
    Write-Success "Logged out successfully"
} catch {
    Write-Error "Logout failed: $_"
}

Write-Host ""
Write-Host "======================================" -ForegroundColor Cyan
Write-Host "Testing Complete!" -ForegroundColor Cyan
Write-Host "======================================" -ForegroundColor Cyan
Write-Host ""
