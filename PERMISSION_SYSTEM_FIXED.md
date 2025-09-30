# Permission System - Fixed & Improved

## 🔒 Security Issue Fixed

### Problem
**Moderators could edit/delete content created by other users!**

This was a serious security issue where:
- ❌ Moderator A could edit/delete posts created by Moderator B
- ❌ Moderator could edit/delete posts created by Admin
- ❌ Moderator could edit/delete monuments created by other users

### Solution
Added **ownership checking** middleware that ensures:
- ✅ Moderators can only edit/delete **their own content**
- ✅ Admins can edit/delete **any content**
- ✅ Proper authorization checks on all edit/delete operations

---

## 📋 Permission Matrix

### Posts

| Action | Admin | Moderator (Own) | Moderator (Others) | User |
|--------|-------|-----------------|-------------------|------|
| **View List** | ✅ All | ✅ All | ✅ All | ❌ |
| **View Detail** | ✅ All | ✅ All | ✅ All | ❌ |
| **Create** | ✅ | ✅ | N/A | ❌ |
| **Edit** | ✅ All | ✅ Own only | ❌ | ❌ |
| **Delete Draft/Pending** | ✅ All | ✅ Own only | ❌ | ❌ |
| **Delete Approved** | ✅ | ❌ | ❌ | ❌ |
| **Approve** | ✅ | ❌ | ❌ | ❌ |
| **Reject** | ✅ | ❌ | ❌ | ❌ |

### Monuments

| Action | Admin | Moderator (Own) | Moderator (Others) | User |
|--------|-------|-----------------|-------------------|------|
| **View List** | ✅ All | ✅ All | ✅ All | ❌ |
| **View Detail** | ✅ All | ✅ All | ✅ All | ❌ |
| **Create** | ✅ | ✅ | N/A | ❌ |
| **Edit** | ✅ All | ✅ Own only | ❌ | ❌ |
| **Delete Draft/Pending** | ✅ All | ✅ Own only | ❌ | ❌ |
| **Delete Approved** | ✅ | ❌ | ❌ | ❌ |
| **Approve** | ✅ | ❌ | ❌ | ❌ |

### Gallery

| Action | Admin | Moderator (Own Monument) | Moderator (Others Monument) | User |
|--------|-------|--------------------------|----------------------------|------|
| **View List** | ✅ All | ✅ All | ✅ All | ❌ |
| **View Detail** | ✅ All | ✅ All | ✅ All | ❌ |
| **Create** | ✅ | ✅ | N/A | ❌ |
| **Edit** | ✅ All | ✅ Own monument only | ❌ | ❌ |
| **Delete** | ✅ All | ✅ Own monument only | ❌ | ❌ |

**Note**: Gallery ownership is checked through the monument. If the gallery belongs to a monument created by the moderator, they can edit/delete it.

### Feedbacks

| Action | Admin | Moderator | User |
|--------|-------|-----------|------|
| **View List** | ✅ All | ✅ All | ❌ |
| **View Detail** | ✅ All | ✅ All | ❌ |
| **Delete** | ✅ All | ✅ All | ❌ |

**Note**: Feedbacks are submitted by public users, so any admin/moderator can manage them.

### Users

| Action | Admin | Moderator | User |
|--------|-------|-----------|------|
| **View List** | ✅ | ❌ | ❌ |
| **View Detail** | ✅ | ❌ | ❌ |
| **Create** | ✅ | ❌ | ❌ |
| **Edit** | ✅ | ❌ | ❌ |
| **Delete** | ✅ | ❌ | ❌ |

### Site Settings

| Action | Admin | Moderator | User |
|--------|-------|-----------|------|
| **View** | ✅ | ❌ | ❌ |
| **Edit** | ✅ | ❌ | ❌ |

---

## 🛡️ Middleware Stack

### 1. AdminMiddleware (`admin`)
- **Purpose**: Check if user is admin or moderator
- **Applied to**: All admin routes
- **Logic**: Allow if `role` is `admin` or `moderator`

### 2. AdminOnlyMiddleware (`admin.only`)
- **Purpose**: Check if user is admin (not moderator)
- **Applied to**: Users management, Site settings
- **Logic**: Allow only if `role` is `admin`

### 3. AdminApprovalMiddleware (`admin.approval`)
- **Purpose**: Check if user can approve content
- **Applied to**: Approve/Reject actions
- **Logic**: Allow only if `role` is `admin`

### 4. PreventApprovedDeletionMiddleware (`prevent.approved.deletion`)
- **Purpose**: Prevent moderators from deleting approved content
- **Applied to**: Delete actions
- **Logic**: 
  - Admin: Can delete anything
  - Moderator: Can only delete draft/pending content

### 5. CheckOwnershipMiddleware (`check.ownership`) **[NEW]**
- **Purpose**: Ensure moderators can only edit/delete their own content
- **Applied to**: Edit, Update, Delete actions
- **Logic**:
  - Admin: Can edit/delete anything
  - Moderator: Can only edit/delete content where `created_by` = their user ID
  - For Gallery: Check if the monument belongs to the moderator

---

## 🔧 Implementation Details

### Routes Configuration

#### Web Routes (routes/web.php)

**Posts:**
```php
Route::get('posts/{post}/edit', [PostController::class, 'edit'])
    ->middleware('check.ownership');
Route::put('posts/{post}', [PostController::class, 'update'])
    ->middleware('check.ownership');
Route::delete('posts/{post}', [PostController::class, 'destroy'])
    ->middleware(['check.ownership', 'prevent.approved.deletion']);
```

**Monuments:**
```php
Route::get('monuments/{monument}/edit', [MonumentController::class, 'edit'])
    ->middleware('check.ownership');
Route::put('monuments/{monument}', [MonumentController::class, 'update'])
    ->middleware('check.ownership');
Route::delete('monuments/{monument}', [MonumentController::class, 'destroy'])
    ->middleware(['check.ownership', 'prevent.approved.deletion']);
```

**Gallery:**
```php
Route::get('gallery/{gallery}/edit', [GalleryController::class, 'edit'])
    ->middleware('check.ownership');
Route::put('gallery/{gallery}', [GalleryController::class, 'update'])
    ->middleware('check.ownership');
Route::delete('gallery/{gallery}', [GalleryController::class, 'destroy'])
    ->middleware('check.ownership');
```

#### API Routes (routes/api.php)

Same middleware applied to API routes for consistency.

### Middleware Logic

**CheckOwnershipMiddleware:**
```php
public function handle(Request $request, Closure $next)
{
    $user = Auth::user();

    // Admin can do anything
    if ($user->role === 'admin') {
        return $next($request);
    }

    // For moderators, check ownership
    if ($user->role === 'moderator') {
        $content = $this->getContentFromRoute($request);
        
        if ($content && $content->created_by !== $user->id) {
            abort(403, 'You can only edit or delete content that you created.');
        }
    }

    return $next($request);
}
```

---

## 🧪 Testing

### Test Case 1: Moderator tries to edit their own post
```
User: Moderator A (ID: 2)
Post: Created by Moderator A (created_by: 2)
Action: Edit
Result: ✅ Allowed
```

### Test Case 2: Moderator tries to edit another moderator's post
```
User: Moderator A (ID: 2)
Post: Created by Moderator B (created_by: 3)
Action: Edit
Result: ❌ Forbidden (403)
Message: "You can only edit or delete content that you created."
```

### Test Case 3: Moderator tries to delete approved post (their own)
```
User: Moderator A (ID: 2)
Post: Created by Moderator A (created_by: 2), Status: approved
Action: Delete
Result: ❌ Forbidden (403)
Message: "Cannot delete approved content. Only administrators can delete approved content."
```

### Test Case 4: Admin edits any post
```
User: Admin (ID: 1)
Post: Created by Moderator A (created_by: 2)
Action: Edit
Result: ✅ Allowed
```

### Test Case 5: Admin deletes approved post
```
User: Admin (ID: 1)
Post: Created by Moderator A (created_by: 2), Status: approved
Action: Delete
Result: ✅ Allowed
```

### Test Case 6: Moderator edits gallery of their monument
```
User: Moderator A (ID: 2)
Monument: Created by Moderator A (created_by: 2)
Gallery: Belongs to that monument
Action: Edit gallery
Result: ✅ Allowed
```

### Test Case 7: Moderator edits gallery of another's monument
```
User: Moderator A (ID: 2)
Monument: Created by Moderator B (created_by: 3)
Gallery: Belongs to that monument
Action: Edit gallery
Result: ❌ Forbidden (403)
```

---

## 📝 Summary of Changes

### Files Created
1. ✅ `app/Http/Middleware/CheckOwnershipMiddleware.php` - New middleware

### Files Modified
1. ✅ `app/Http/Kernel.php` - Registered new middleware
2. ✅ `routes/web.php` - Applied middleware to routes
3. ✅ `routes/api.php` - Applied middleware to API routes

### Middleware Applied To
- ✅ Posts: edit, update, delete
- ✅ Monuments: edit, update, delete
- ✅ Gallery: edit, update, delete

---

## 🎯 Benefits

### Security
- ✅ Prevents unauthorized access to other users' content
- ✅ Enforces proper ownership checks
- ✅ Protects against privilege escalation

### User Experience
- ✅ Clear error messages
- ✅ Consistent behavior across web and API
- ✅ Moderators can manage their own content freely

### Maintainability
- ✅ Centralized permission logic in middleware
- ✅ Easy to understand and modify
- ✅ Well-documented

---

## 🚀 Next Steps (Optional)

1. **Add UI indicators**: Show/hide edit/delete buttons based on ownership
2. **Add audit log**: Track who edited/deleted what
3. **Add bulk operations**: Allow admins to bulk approve/delete
4. **Add role-based views**: Show different content based on role

---

**Status**: ✅ Fixed & Tested  
**Security Level**: 🔒 High  
**Date**: 2025-09-30

