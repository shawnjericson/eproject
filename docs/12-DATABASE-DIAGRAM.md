# 🗄️ Global Heritage CMS - Database Schema

## 🎨 Visual Database Diagram

```
                    ┌─────────────────────────────────────────────────────────────┐
                    │                    🏛️ GLOBAL HERITAGE CMS                   │
                    │                      Database Schema                        │
                    └─────────────────────────────────────────────────────────────┘

┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   👥 USERS      │    │   📝 POSTS      │    │  🏛️ MONUMENTS   │
├─────────────────┤    ├─────────────────┤    ├─────────────────┤
│ 🔑 id (PK)      │    │ 🔑 id (PK)      │    │ 🔑 id (PK)      │
│ 👤 name         │    │ 📄 title        │    │ 🏛️ title        │
│ 📧 email (UK)   │    │ 📝 content      │    │ 📖 description  │
│ 👑 role         │    │ 🖼️ image        │    │ 📚 history      │
│ ✅ status       │    │ 👤 created_by   │    │ 📝 content      │
│ 🔐 password     │    │ 📊 status       │    │ 📍 location     │
│ ❓ security_q   │    │ 📅 published_at │    │ 🌍 latitude     │
│ 🔒 security_a   │    │ ❌ rejected_by  │    │ 🌍 longitude    │
│ ⏰ timestamps   │    │ ⏰ timestamps   │    │ ⭐ world_wonder │
└─────────────────┘    │ 🗑️ deleted_at   │    │ 🖼️ image        │
         │              └─────────────────┘    │ 👤 created_by   │
         │                       │              │ 📊 status      │
         │                       │              │ ❌ rejected_by │
         │                       │              │ ⏰ timestamps  │
         │                       │              │ 🗑️ deleted_at  │
         │                       │              └─────────────────┘
         │                       │                       │
         │                       │                       │
         │              ┌─────────────────┐              │
         │              │ 🌍 POST_TRANS   │              │
         │              ├─────────────────┤              │
         │              │ 🔑 id (PK)      │              │
         │              │ 🔗 post_id (FK) │              │
         │              │ 🌐 language     │              │
         │              │ 📄 title        │              │
         │              │ 📝 description  │              │
         │              │ 📝 content      │              │
         │              │ ⏰ timestamps   │              │
         │              └─────────────────┘              │
         │                                               │
         │              ┌─────────────────┐              │
         │              │ 🌍 MONUMENT_TRANS│             │
         │              ├─────────────────┤              │
         │              │ 🔑 id (PK)      │              │
         │              │ 🔗 monument_id  │              │
         │              │ 🌐 language     │              │
         │              │ 📄 title        │              │
         │              │ 📝 description  │              │
         │              │ 📚 history      │              │
         │              │ 📝 content      │              │
         │              │ 📍 location     │              │
         │              │ ⏰ timestamps   │              │
         │              └─────────────────┘              │
         │                                               │
         │                       │                       │
         │                       │                       │
         │              ┌─────────────────┐              │
         │              │  🖼️ GALLERY     │              │
         │              ├─────────────────┤              │
         │              │ 🔑 id (PK)      │              │
         │              │ 🔗 monument_id  │◄─────────────┘
         │              │ 📄 title        │
         │              │ 🖼️ image_path   │
         │              │ 📝 description  │
         │              │ ⏰ timestamps   │
         │              └─────────────────┘
         │
         │
         │    ┌─────────────────┐    ┌─────────────────┐
         │    │ 💬 FEEDBACKS    │    │ 📞 CONTACTS     │
         │    ├─────────────────┤    ├─────────────────┤
         │    │ 🔑 id (PK)      │    │ 🔑 id (PK)      │
         │    │ 👤 name         │    │ 👤 name         │
         │    │ 📧 email        │    │ 📧 email        │
         │    │ 💬 message      │    │ 📋 subject      │
         │    │ ⭐ rating       │    │ 💬 message      │
         │    │ 📊 status       │    │ 📊 status       │
         │    │ 🔗 monument_id  │    │ 💬 admin_reply  │
         │    │ 👁️ viewed_at    │    │ ⏰ replied_at   │
         │    │ ⏰ timestamps   │    │ 👤 replied_by   │
         │    └─────────────────┘    │ ⏰ timestamps   │
         │                           └─────────────────┘
         │
         │
         │    ┌─────────────────┐    ┌─────────────────┐
         │    │ 🔔 NOTIFICATIONS│    │ ⚙️ SITE_SETTINGS│
         │    ├─────────────────┤    ├─────────────────┤
         │    │ 🔑 id (PK)      │    │ 🔑 id (PK)      │
         │    │ 👤 user_id (FK) │    │ 🔑 key (UK)     │
         │    │ 📋 type         │    │ 💾 value        │
         │    │ 📄 title        │    │ 📝 description  │
         │    │ 💬 message      │    │ 📂 category     │
         │    │ 📊 data         │    │ 🔧 type         │
         │    │ 👁️ is_read      │    │ 🔢 min/max      │
         │    │ ⏰ read_at      │    │ ⏰ timestamps   │
         │    │ ⏰ timestamps   │    └─────────────────┘
         │    └─────────────────┘
         │
         │
         │    ┌─────────────────┐
         │    │ 📊 VISITOR_LOGS │
         │    ├─────────────────┤
         │    │ 🔑 id (PK)      │
         │    │ 🌐 ip_address   │
         │    │ 💻 user_agent   │
         │    │ ⏰ visited_at   │
         │    │ ⏰ timestamps   │
         │    └─────────────────┘
```

## 🔗 Relationship Flow

```
                    ┌─────────────────────────────────────────┐
                    │            🎯 CORE ENTITIES             │
                    └─────────────────────────────────────────┘

    ┌─────────────┐         ┌─────────────┐         ┌─────────────┐
    │   👥 USERS  │         │  📝 POSTS   │         │ 🏛️ MONUMENTS│
    │             │         │             │         │             │
    │ 👑 Admin    │────────▶│ 📄 Articles │         │ 🏛️ Heritage │
    │ 👨‍💼 Moderator│         │ 📊 Status   │         │ 📍 Location │
    │ ✅ Active   │         │ 🌍 Multilang│         │ 🌍 Multilang│
    └─────────────┘         └─────────────┘         └─────────────┘
           │                        │                        │
           │                        │                        │
           │                        ▼                        ▼
           │                ┌─────────────┐         ┌─────────────┐
           │                │🌍 POST_TRANS│         │🌍 MONUMENT_ │
           │                │             │         │    TRANS    │
           │                │ 🌐 Language │         │             │
           │                │ 📄 Title    │         │ 🌐 Language │
           │                │ 📝 Content  │         │ 📄 Title    │
           │                └─────────────┘         │ 📝 Content  │
           │                                        └─────────────┘
           │                                                │
           │                                                ▼
           │                                        ┌─────────────┐
           │                                        │  🖼️ GALLERY  │
           │                                        │             │
           │                                        │ 🖼️ Images   │
           │                                        │ 📝 Descriptions│
           │                                        └─────────────┘
           │
           │    ┌─────────────┐    ┌─────────────┐    ┌─────────────┐
           │    │💬 FEEDBACKS │    │ 📞 CONTACTS │    │🔔 NOTIFICATIONS│
           │    │             │    │             │    │             │
           │    │ ⭐ Rating   │    │ 📋 Subject  │    │ 📄 Messages │
           │    │ 💬 Message  │    │ 💬 Message  │    │ 👁️ Read/Unread│
           │    │ 📊 Status   │    │ 📊 Status   │    │ 👤 User     │
           │    └─────────────┘    │ 💬 Reply    │    └─────────────┘
           │                       └─────────────┘
           │
           │    ┌─────────────┐    ┌─────────────┐
           │    │⚙️ SITE_SETTINGS│  │📊 VISITOR_LOGS│
           │    │             │    │             │
           │    │ 🔧 Config   │    │ 🌐 IP Track │
           │    │ 💾 Values   │    │ 💻 Browser  │
           │    │ 📂 Category │    │ ⏰ Visit Time│
           │    └─────────────┘    └─────────────┘
```

## 🎨 Data Flow Visualization

```
┌─────────────────────────────────────────────────────────────────┐
│                    🌍 MULTILINGUAL SYSTEM                      │
└─────────────────────────────────────────────────────────────────┘

📝 POSTS ──────────────┐                    ┌────────────── 🏛️ MONUMENTS
    │                  │                    │
    ▼                  │                    ▼
🌍 POST_TRANS ─────────┼─────────────────── 🌍 MONUMENT_TRANS
    │                  │                    │
    │  🌐 vi (Vietnamese)                   │  🌐 vi (Vietnamese)
    │  🌐 en (English)                      │  🌐 en (English)
    │  🌐 fr (French)                       │  🌐 fr (French)
    │  🌐 zh (Chinese)                      │  🌐 zh (Chinese)
    └───────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                    📊 CONTENT MANAGEMENT                       │
└─────────────────────────────────────────────────────────────────┘

👥 USERS (Admin/Moderator)
    │
    ├── 📝 Creates POSTS
    │   └── 📊 Status: draft → pending → approved/rejected
    │
    ├── 🏛️ Creates MONUMENTS
    │   ├── 📊 Status: draft → pending → approved/rejected
    │   ├── 🖼️ Uploads GALLERY images
    │   └── 📍 Sets location coordinates
    │
    ├── 💬 Manages FEEDBACKS
    │   └── 📊 Status: pending → approved/rejected
    │
    ├── 📞 Handles CONTACTS
    │   └── 💬 Replies to messages
    │
    └── 🔔 Receives NOTIFICATIONS
        └── 👁️ Mark as read/unread

┌─────────────────────────────────────────────────────────────────┐
│                    🌐 PUBLIC INTERFACE                         │
└─────────────────────────────────────────────────────────────────┘

👤 VISITORS
    │
    ├── 📖 Read POSTS (published only)
    ├── 🏛️ Browse MONUMENTS (approved only)
    ├── 🖼️ View GALLERY images
    ├── 💬 Submit FEEDBACKS
    ├── 📞 Send CONTACTS
    └── 📊 Track VISITOR_LOGS
```

## Key Relationships

### 1. **User Relationships**
- `users` → `posts` (created_by, rejected_by)
- `users` → `monuments` (created_by, rejected_by)
- `users` → `contacts` (replied_by)
- `users` → `user_notifications` (user_id)

### 2. **Content Relationships**
- `posts` → `post_translations` (post_id)
- `monuments` → `monument_translations` (monument_id)
- `monuments` → `gallery` (monument_id)
- `monuments` → `feedbacks` (monument_id)

### 3. **Translation System**
- Each post can have multiple translations
- Each monument can have multiple translations
- Language codes stored as varchar(5)
- Unique constraints on (parent_id, language)

### 4. **Approval Workflow**
- Posts: draft → pending → approved/rejected
- Monuments: draft → pending → approved/rejected
- Feedbacks: pending → approved/rejected
- Contacts: new → read → replied

## Database Features

### 🔐 **Security**
- Role-based access (admin/moderator)
- API token authentication
- Password reset tokens
- Security questions

### 🌍 **Multilingual**
- Separate translation tables
- Language-specific content
- Flexible language support

### 📊 **Content Management**
- Approval workflows
- Soft deletes
- Status tracking
- Rich content support

### 📈 **Analytics**
- Visitor tracking
- Feedback system
- Notification management

### ⚙️ **Configuration**
- Dynamic settings
- Admin panel integration
- Flexible metadata storage
