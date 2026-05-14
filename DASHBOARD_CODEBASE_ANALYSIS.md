# Dashboard Codebase Analysis - Perjanjian Kinerja System

## Executive Summary
The application implements a **role-based dashboard system** with 4 main dashboard types. Currently, **2 dashboard views are missing** despite having controller methods and routes defined. The existing dashboards follow mixed architectural patterns (some standalone HTML, others using layout extends).

---

## 1. ALL DASHBOARD VIEW FILES & STRUCTURE

### ✅ Existing View Files

#### 1.1 User/Staff Dashboard
- **File:** `resources/views/home.blade.php`
- **Route:** GET `/` (when redirected)
- **Status:** ✅ Exists (Standalone HTML)
- **Controller:** None (static view)
- **Structure:**
  - Inline CSS (no layout extend)
  - Header with logo, navigation, icons
  - Profile dropdown menu
  - Welcome section
  - Multiple content areas

#### 1.2 Director Dashboard
- **File:** `resources/views/dashboard/direktur.blade.php`
- **Routes:** 
  - GET `/dashboard/direktur` 
  - GET `/dashboard/direktur/perjanjian-kinerja`
- **Status:** ✅ Exists (Standalone HTML)
- **Controller:** `DirekturDashboardController@index` / `@perjanjianKinerja`
- **Structure:**
  - Inline CSS styling
  - White header with navigation
  - Search input field
  - **Status Filter Cards:** All, Approved, Rejected, Waiting
  - **Data Table:** Lists perjanjian with columns:
    - Nama Pegawai (Employee Name)
    - Jabatan (Position)
    - Tanggal (Date)
    - Status badges
  - Profile menu dropdown
  - **Features:** Search, filtering, pagination

#### 1.3 Director - Contract Detail View
- **File:** `resources/views/dashboard/perjanjian-show.blade.php`
- **Route:** GET `/dashboard/direktur/perjanjian/{id}`
- **Status:** ✅ Exists (Standalone HTML)
- **Controller:** `DirekturDashboardController@showPerjanjian`
- **Structure:**
  - Single contract display
  - Header with back button
  - Full perjanjian details
  - Action buttons (approve/reject)

#### 1.4 Director - Contract List with Detail
- **File:** `resources/views/dashboard/perjanjian-kinerja.blade.php`
- **Route:** GET `/dashboard/direktur/perjanjian-kinerja`
- **Status:** ✅ Exists (Standalone HTML)
- **Controller:** `DirekturDashboardController@perjanjianKinerja`
- **Structure:**
  - Similar to direktur.blade.php
  - Enhanced search and filter functionality
  - Perjanjian list table

#### 1.5 Deputy Director Dashboard
- **File:** `resources/views/dashboard/wadir.blade.php`
- **Route:** GET `/dashboard/wadir`
- **Status:** ✅ Exists (Standalone HTML)
- **Controller:** `DashboardController@wadir`
- **Structure:**
  - Welcome message
  - **4 Stat Cards:**
    - Total Perjanjian
    - Approved (Disetujui)
    - Waiting (Menunggu Persetujuan)
    - Rejected (Ditolak)
  - **Menu Items:** Links to:
    - Perjanjian Kinerja
    - Laporan Kinerja
  - Profile menu
  - Responsive grid layout

#### 1.6 Admin Dashboard
- **File:** `resources/views/admin/dashboard.blade.php`
- **Route:** Configured in admin routes
- **Status:** ✅ Exists (Uses `@extends('admin.layout')`)
- **Controller:** `App\Http\Controllers\Admin\AdminController@index`
- **Structure:** (Bootstrap 5)
  - **Alert:** Pending users notification
  - **6 Stat Cards (3x2 grid):**
    - Total Users
    - Total Perjanjian
    - Total Jabatan (Positions)
    - Total Program
    - Total Kegiatan (Activities)
    - Total SubKegiatan (Sub-activities)
  - **3 Data Tables:**
    - Recent Users (10 items)
    - Recent Perjanjian (5 items)
    - Jabatan List
  - **3 Modals** (clickable cards):
    - Program details modal
    - Kegiatan details modal
    - SubKegiatan details modal
  - **Live Search:** Search perjanjian table

### ❌ MISSING View Files

#### 1.7 Head of Department Dashboard
- **Expected File:** `resources/views/dashboard/kabag-kabid.blade.php`
- **Route:** GET `/dashboard/kabag.kabid`
- **Status:** ❌ **MISSING** (View doesn't exist!)
- **Controller:** `DashboardController@kabagKabid` ✅ (Exists and has data)
- **Issue:** 
  - Route is configured ✅
  - Controller method exists ✅
  - **View file is missing** ❌
  - Will throw error: `View [dashboard.kabag-kabid] not found`

#### 1.8 Staff Dashboard
- **Expected File:** `resources/views/dashboard/katimker-staf.blade.php`
- **Route:** GET `/dashboard/katimker.staf`
- **Status:** ❌ **MISSING** (View doesn't exist!)
- **Controller:** `DashboardController@katimkerStaf` ✅ (Exists and has data)
- **Issue:**
  - Route is configured ✅
  - Controller method exists ✅
  - **View file is missing** ❌
  - Will throw error: `View [dashboard.katimker-staf] not found`

---

## 2. ALL DASHBOARD CONTROLLERS & DATA STRUCTURE

### 2.1 DashboardController
**File:** `app/Http/Controllers/DashboardController.php`

#### Method: `index()`
- **Purpose:** Central redirect based on user role
- **Logic:** Checks user's jabatan and redirects to appropriate dashboard
- **Role Routing:**
  - `Direktur` → `/dashboard/direktur`
  - `Wakil Direktur Umum dan Keuangan` → `/dashboard/wadir`
  - `Wakil Direktur Pelayanan` → `/dashboard/wadir`
  - `Kabag*` or `Kepala Bagian*` → `/dashboard/kabag.kabid`
  - `Kabid*` or `Kepala Bidang*` → `/dashboard/kabag.kabid`
  - `Kasi*` or `Kepala Seksi*` → `/dashboard/katimker.staf`
  - Others → `/` (home)

#### Method: `wadir()`
- **Route:** GET `/dashboard/wadir`
- **Access:** Users with jabatan = "Wakil Direktur Umum dan Keuangan" or "Wakil Direktur Pelayanan"
- **Data Passed:**
  ```php
  $totalPerjanjian          // Total contracts where pihak2 = user
  $perjanjianApproved       // pihak2_signature != null & rejected = false
  $perjanjianWaiting        // pihak2_signature = null & rejected = false
  $perjanjianRejected       // rejected = true
  ```
- **View:** `dashboard.wadir`
- **Database Query:** 
  - Filters perjanjian where `pihak2_name = user->nama` or `pihak2_nip = user->nip`
  - Counts based on signature and rejection status

#### Method: `kabagKabid()`
- **Route:** GET `/dashboard/kabag.kabid`
- **Access:** Users with "Kabag", "Kepala Bagian", "Kabid", or "Kepala Bidang" in jabatan
- **Data Passed:**
  ```php
  $totalPerjanjian          // Total contracts created by user
  $perjanjianApproved       // Approved status
  $perjanjianWaiting        // Waiting status
  $perjanjianRejected       // Rejected status
  ```
- **View:** `dashboard.kabag-kabid` ⚠️ **MISSING**
- **Database Query:**
  - Filters perjanjian where `user_id = auth()->user()->id`
  - Counts based on signature and rejection status

#### Method: `katimkerStaf()`
- **Route:** GET `/dashboard/katimker.staf`
- **Access:** Users with "Kasi", "Kepala Seksi" or other staff roles
- **Data Passed:**
  ```php
  $totalPerjanjian          // Total contracts created by user
  $perjanjianApproved       // Approved status
  $perjanjianWaiting        // Waiting status
  $perjanjianRejected       // Rejected status
  ```
- **View:** `dashboard.katimker-staf` ⚠️ **MISSING**
- **Database Query:**
  - Filters perjanjian where `user_id = auth()->user()->id`
  - Counts based on signature and rejection status

---

### 2.2 DirekturDashboardController
**File:** `app/Http/Controllers/DirekturDashboardController.php`

#### Method: `index()`
- **Purpose:** Main director dashboard entry point
- **Behavior:** Calls `perjanjianKinerja()` directly
- **Route:** GET `/dashboard/direktur`

#### Method: `perjanjianKinerja(Request $request)`
- **Route:** GET `/dashboard/direktur/perjanjian-kinerja`
- **Purpose:** Display list of contracts for director approval
- **Features:**
  - Real-time search by: name, date, position
  - Filter by status: all, approved, rejected, waiting
  - AJAX support for dynamic updates
  - Pagination (10 per page)
- **Data Passed:**
  ```php
  $counts = [
    'all' => total_count,
    'approved' => approved_count,
    'rejected' => rejected_count,
    'waiting' => waiting_count
  ]
  
  $perjanjians               // Paginated collection
  $notifications            // Recent approvals/rejections
  ```
- **Response Format:**
  - HTML response for page load
  - JSON response for AJAX requests
  - Mapped data includes: `id`, `pihak1_name`, `jenis_perjanjian`, `tanggal`, `status`

#### Method: `perjanjianList(Request $request)`
- **Route:** GET `/dashboard/direktur/perjanjian-list`
- **Purpose:** Alternative list view with page title based on filter
- **Data Passed:**
  ```php
  $pageTitle  // "Total Laporan Diterima", "Disetujui", "Ditolak", or "Menunggu"
  ```

#### Method: `showPerjanjian($id)`
- **Route:** GET `/dashboard/direktur/perjanjian/{id}`
- **Purpose:** Display single contract details
- **Authorization:** Only pihak kedua (second party) can view
- **Data Passed:**
  ```php
  $perjanjian                // Full contract object
  $status                    // 'waiting', 'approved', or 'rejected'
  $rejection_reason          // Reason if rejected
  ```
- **Status Determination Logic:**
  - Queries from DB (fresh data)
  - Logs perjanjian status for debugging
  - Returns 403 if user is not pihak2

#### Method: `printPerjanjian($id)`
- **Route:** GET `/dashboard/direktur/perjanjian/{id}/print`
- **Purpose:** Print contract as document
- **Returns:** Printable HTML view

#### Method: `downloadPerjanjian($id)`
- **Route:** GET `/dashboard/direktur/perjanjian/{id}/download`
- **Purpose:** Download contract as PDF
- **Returns:** PDF file

#### Method: `approvePerjanjian($id)` [POST]
- **Route:** POST `/dashboard/direktur/perjanjian/{id}/approve`
- **Purpose:** Approve contract with signature
- **Data Requirements:**
  - Signature data (from request)
- **Database Changes:**
  - Sets `pihak2_signature`
  - Sets `rejected = false`

#### Method: `rejectPerjanjian($id)` [POST]
- **Route:** POST `/dashboard/direktur/perjanjian/{id}/reject`
- **Purpose:** Reject contract with reason
- **Data Requirements:**
  - Rejection reason
- **Database Changes:**
  - Sets `rejected = true`
  - Sets `rejection_reason`

#### Method: `laporanKinerja()`
- **Route:** GET `/dashboard/direktur/laporan-kinerja`
- **Purpose:** Show performance reports (laporan)
- **Data:** Similar to contracts but for Laporan model

---

### 2.3 AdminController
**File:** `app/Http/Controllers/Admin/AdminController.php`

#### Method: `index()`
- **Route:** GET `/admin/dashboard` (in admin routes)
- **Purpose:** Main admin dashboard
- **Data Passed:**
  ```php
  // Counts
  $totalUsers               // User::count()
  $totalPerjanjian          // Perjanjian::count()
  $totalNotifications       // Notification::count()
  
  // Recent data
  $users                    // Last 10 users
  $recentPerjanjian         // Last 5 perjanjian with relationships
  
  // Master data (REALTIME from DB, not snapshots)
  $allPrograms              // Active programs from programs table
  $allKegiatan              // Active kegiatan with relationships
  $allSubKegiatan           // Active sub_kegiatan with relationships
  
  // Counts
  $totalPrograms            // Count of active programs
  $totalKegiatan            // Count of active kegiatan
  $totalSubKegiatan         // Count of active sub_kegiatan
  
  // Status counts
  $totalWaiting             // Perjanjian waiting approval
  $totalApproved            // Perjanjian approved
  $totalRejected            // Perjanjian rejected
  
  // Stats with user counts
  $jabatanStats             // Jabatan with users_count
  ```
- **Features:**
  - Alert for pending users
  - Clickable stat cards that open modals
  - Live search on perjanjian table
  - Sticky table headers
  - Responsive grid layout

---

## 3. LAYOUT & TEMPLATE STRUCTURE

### Main Application Layout
- **File:** `resources/views/layouts/app.blade.php`
- **Purpose:** Base layout for authenticated area
- **Usage:** Extended by some views (optional)

### Admin Layout
- **File:** `resources/views/admin/layout.blade.php`
- **Purpose:** Admin-specific layout
- **Usage:** Extended by admin dashboard and admin pages
- **Features:**
  - Bootstrap 5 integration
  - Sidebar navigation
  - Admin-specific styling

### Current Dashboard Layouts

#### Standalone Views (No Layout Extends)
Most dashboards use **inline HTML** without extending a base layout:
- `home.blade.php` ← Standalone
- `direktur.blade.php` ← Standalone
- `wadir.blade.php` ← Standalone
- `perjanjian-kinerja.blade.php` ← Standalone
- `perjanjian-show.blade.php` ← Standalone

**Pros:** Full control over HTML structure
**Cons:** Code duplication (header, navigation, styles repeated in each file)

#### Layout-Based Views
- `admin/dashboard.blade.php` ← Uses `@extends('admin.layout')`

**Pros:** DRY principle, shared header/footer/navigation
**Cons:** Less flexible, dependent on parent layout

---

## 4. BUTTONS & ACTIONS IN EACH DASHBOARD

### Direktur Dashboard (direktur.blade.php)

| Button/Element | Action | Route | Method |
|---|---|---|---|
| **Search Input** | Search contracts by name/date/status | None (client-side) | GET |
| **Status Card: All** | Filter to show all contracts | AJAX filter | GET (AJAX) |
| **Status Card: Disetujui** | Filter to approved contracts | AJAX filter | GET (AJAX) |
| **Status Card: Ditolak** | Filter to rejected contracts | AJAX filter | GET (AJAX) |
| **Status Card: Menunggu** | Filter to waiting contracts | AJAX filter | GET (AJAX) |
| **Table Row Click** | View contract details | `/dashboard/direktur/perjanjian/{id}` | GET |
| **Profile Icon** | Open profile menu | None (modal toggle) | - |
| **Profile > Profil Saya** | Go to profile page | `route('profil')` | GET |
| **Profile > Kontak** | Go to contact page | `route('kontak')` | GET |
| **Profile > Panduan** | Go to guide page | `route('panduan')` | GET |
| **Profile > Tentang** | Go to about page | `route('tentang')` | GET |
| **Profile > Settings** | Go to settings page | `route('settings')` | GET |
| **Logout Icon** | Logout confirmation modal | None (modal) | - |
| **Logout > Yes** | Confirm logout | `route('logout')` | POST |
| **Logout > No** | Cancel logout | None | - |

### Perjanjian Detail View (perjanjian-show.blade.php)

| Button/Element | Action | Route | Method |
|---|---|---|---|
| **Back Button** | Go back to list | `/dashboard/direktur` | GET |
| **Approve Button** | Approve with signature | `/dashboard/direktur/perjanjian/{id}/approve` | POST |
| **Reject Button** | Reject with reason | `/dashboard/direktur/perjanjian/{id}/reject` | POST |
| **Print Button** | Print contract | `/dashboard/direktur/perjanjian/{id}/print` | GET |
| **Download Button** | Download PDF | `/dashboard/direktur/perjanjian/{id}/download` | GET |

### Wadir Dashboard (wadir.blade.php)

| Button/Element | Action | Route | Method |
|---|---|---|---|
| **Stat Card: Total** | (Informational only) | - | - |
| **Stat Card: Approved** | (Informational only) | - | - |
| **Stat Card: Waiting** | (Informational only) | - | - |
| **Stat Card: Rejected** | (Informational only) | - | - |
| **Menu: Perjanjian Kinerja** | View contracts | `/dashboard/wadir/perjanjian-kinerja` | GET |
| **Menu: Laporan Kinerja** | View reports | `/dashboard/wadir/laporan-kinerja` | GET |
| **Profile Icon** | Open profile menu | None (modal) | - |
| **Profile Menu Items** | Same as Direktur | - | - |
| **Logout Icon** | Logout confirmation | None (modal) | - |

### Admin Dashboard (admin/dashboard.blade.php)

| Button/Element | Action | Route | Method |
|---|---|---|---|
| **Pending Users Alert** | Show count of pending users | - | - |
| **"Lihat sekarang" Link** | Go to pending users | `route('admin.users.pending')` | GET |
| **Total Users Card** | (Informational) | - | - |
| **Total Perjanjian Card** | (Informational) | - | - |
| **Total Jabatan Card** | (Informational) | - | - |
| **Program Card** | Open program modal | None (modal toggle) | - |
| **Kegiatan Card** | Open kegiatan modal | None (modal toggle) | - |
| **SubKegiatan Card** | Open sub-kegiatan modal | None (modal toggle) | - |
| **Users Table: Lihat Semua** | View all users | `route('admin.users.index')` | GET |
| **Jabatan Table: Kelola** | Manage jabatan | `route('admin.jabatan.index')` | GET |
| **Perjanjian Search** | Live search table | None (client-side) | - |
| **Modal: Close** | Close program/kegiatan/sub-kegiatan modal | None (modal) | - |

### Missing Dashboards

#### Kabag.Kabid Dashboard (NOT IMPLEMENTED)
- Controllers has data ready
- View doesn't exist yet
- Should show: Total, Approved, Waiting, Rejected perjanjian

#### Katimker.Staf Dashboard (NOT IMPLEMENTED)
- Controllers has data ready
- View doesn't exist yet
- Should show: Total, Approved, Waiting, Rejected perjanjian

---

## 5. MISSING ROUTES & CONTROLLER METHODS

### ✅ All Routes Are Defined
All dashboard routes exist in `routes/web.php` and are properly configured.

### ❌ Missing View Files

| Route | Controller Method | View File | Status |
|---|---|---|---|
| `/dashboard/kabag.kabid` | `DashboardController@kabagKabid` | `dashboard.kabag-kabid` | ❌ Missing |
| `/dashboard/katimker.staf` | `DashboardController@katimkerStaf` | `dashboard.katimker-staf` | ❌ Missing |

### What Needs To Be Done:
1. **Create** `resources/views/dashboard/kabag-kabid.blade.php`
2. **Create** `resources/views/dashboard/katimker-staf.blade.php`
3. Both should display the 4 stat cards (Total, Approved, Waiting, Rejected)
4. Can be modeled after `wadir.blade.php`

---

## 6. MIDDLEWARE & AUTHORIZATION

### Authentication Middleware
```php
Route::middleware(['auth'])->group(function () {
    // All dashboard routes protected by 'auth' middleware
});
```

### Role-Based Middleware
```php
Route::middleware(['check.jabatan:Direktur'])->group(function () {
    // Only users with jabatan = 'Direktur'
});
```

### Middleware Locations
**File:** `app/Http/Middleware/` (need to verify exact middleware class)

---

## 7. STATUS LOGIC IN DATABASE

### Perjanjian Status Determination

Status is determined by checking two fields:

```php
if (!empty($perjanjian->rejected) && $perjanjian->rejected == true) {
    $status = 'rejected';  // Red badge
}
elseif (!empty($perjanjian->pihak2_signature)) {
    $status = 'approved';  // Green badge
}
else {
    $status = 'waiting';   // Orange badge
}
```

### Status Values in Database

| Field | Value | Status |
|---|---|---|
| `pihak2_signature` | NULL | Waiting/Not signed |
| `pihak2_signature` | Not NULL | Signed |
| `rejected` | false/0/null | Not rejected |
| `rejected` | true/1 | Rejected |

### Combined Status Matrix

| pihak2_signature | rejected | Result |
|---|---|---|
| NULL | false | ⏳ **WAITING** |
| NOT NULL | false | ✅ **APPROVED** |
| ANY | true | ❌ **REJECTED** |

---

## 8. AUTHENTICATION FLOW

```
User
  ↓
Login Form (/login)
  ↓
LoginController@login [POST /login]
  ↓
User Authenticated
  ↓
Redirected to / (home)
  ↓
DashboardController@index checks user.jabatan
  ↓
  ├─ Direktur → /dashboard/direktur
  ├─ Wadir → /dashboard/wadir
  ├─ Kabag/Kabid → /dashboard/kabag.kabid
  ├─ Katimker/Staf → /dashboard/katimker.staf
  └─ Other → /home (static dashboard)
  ↓
Dashboard Displayed with Role-Specific Data
```

---

## 9. DESIGN & COLOR SYSTEM

### Primary Colors
| Color | Hex | Usage |
|---|---|---|
| Teal | #00B5A0 | Primary buttons, icons |
| Dark Teal | #008F7E | Hover states |
| Green | #009970 | Navigation text |
| Dark Blue | #1B2A41 | Body text |

### Status Colors
| Status | Color | Hex |
|---|---|---|
| Approved | Green | #00B050 |
| Waiting | Orange | #FFA500 |
| Rejected | Red | #FF2E2E |

### Background Colors
| Element | Color | Hex |
|---|---|---|
| Page Background | Light Cyan | #E3F8F6, #E6FAF7 |
| Cards | White | #FFFFFF |
| Card Hover | Light Gray | #f8f9fa |
| Stat Card BG | Gradient | 135deg |

### Typography
- **Font Family:** 'Poppins', sans-serif
- **Header Sizes:** 36px-42px (main), 20px-24px (sections)
- **Body Text:** 14px-16px
- **Font Weights:** 400, 600, 700, 800

---

## 10. DATA FLOW DIAGRAM

```
User Login
    ↓
Authentication (/login)
    ↓
Redirected to Dashboard Router
    ↓
DashboardController@index
    ├─ Check user.jabatan
    └─ Redirect to appropriate controller
    ↓
    ├─→ DirekturDashboardController
    │   ├─ Fetch Perjanjian where pihak2 = user
    │   ├─ Count statuses
    │   └─ Return dashboard.direktur view
    │
    ├─→ DashboardController@wadir
    │   ├─ Fetch Perjanjian where pihak2 = user
    │   ├─ Count statuses
    │   └─ Return dashboard.wadir view
    │
    ├─→ DashboardController@kabagKabid
    │   ├─ Fetch Perjanjian where user_id = user->id
    │   ├─ Count statuses
    │   └─ Return dashboard.kabag-kabid view (MISSING)
    │
    └─→ DashboardController@katimkerStaf
        ├─ Fetch Perjanjian where user_id = user->id
        ├─ Count statuses
        └─ Return dashboard.katimker-staf view (MISSING)
```

---

## Summary of Findings

### ✅ What's Implemented
1. **4 Dashboard Controllers** with all methods and data logic
2. **5 Dashboard Views** fully functional
3. **All Routes** properly configured
4. **Role-based Routing** working (via middleware)
5. **Admin Dashboard** with modals and live search
6. **Director Contract Management** with approve/reject flows

### ❌ What's Missing
1. **2 Dashboard Views** not created:
   - `dashboard.kabag-kabid` 
   - `dashboard.katimker-staf`

### ⚠️ Architecture Issues
1. Most views use **inline HTML** instead of layout extends (code duplication)
2. Mixed CSS approaches (inline styles vs separate files)
3. Some inconsistency in response types (HTML vs JSON)

### 🎯 Next Steps for UI Updates
1. Create missing dashboard views
2. Consider refactoring to use consistent layout structure
3. Consolidate CSS into external files
4. Ensure all buttons and actions are functional
5. Update designs to match mockup specifications
