# Database Schema Diagram - Global Heritage CMS

```mermaid
erDiagram
    users {
        bigint id PK
        varchar name
        varchar email UK
        varchar avatar
        varchar phone
        text bio
        varchar address
        date date_of_birth
        timestamp email_verified_at
        varchar password
        enum role
        enum status
        varchar remember_token
        varchar security_question_1
        varchar security_answer_1
        varchar security_question_2
        varchar security_answer_2
        varchar security_question_3
        varchar security_answer_3
        timestamp created_at
        timestamp updated_at
    }

    posts {
        bigint id PK
        varchar title
        longtext content
        varchar image
        bigint created_by FK
        enum status
        timestamp published_at
        text rejection_reason
        timestamp rejected_at
        bigint rejected_by FK
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    post_translations {
        bigint id PK
        bigint post_id FK
        varchar language
        varchar title
        text description
        longtext content
        timestamp created_at
        timestamp updated_at
    }

    monuments {
        bigint id PK
        varchar title
        text description
        text history
        longtext content
        varchar location
        text map_embed
        varchar zone
        decimal latitude
        decimal longitude
        boolean is_world_wonder
        varchar image
        bigint created_by FK
        enum status
        text rejection_reason
        timestamp rejected_at
        bigint rejected_by FK
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    monument_translations {
        bigint id PK
        bigint monument_id FK
        varchar language
        varchar title
        text description
        text history
        longtext content
        varchar location
        timestamp created_at
        timestamp updated_at
    }

    gallery {
        bigint id PK
        bigint monument_id FK
        varchar title
        varchar image_path
        text description
        timestamp created_at
        timestamp updated_at
    }

    feedbacks {
        bigint id PK
        varchar name
        varchar email
        text message
        int rating
        enum status
        bigint monument_id FK
        timestamp viewed_at
        timestamp created_at
        timestamp updated_at
    }

    contacts {
        bigint id PK
        varchar name
        varchar email
        varchar subject
        text message
        enum status
        text admin_reply
        timestamp replied_at
        bigint replied_by FK
        timestamp created_at
        timestamp updated_at
    }

    site_settings {
        bigint id PK
        varchar key UK
        text value
        text description
        varchar category
        varchar type
        int min
        int max
        timestamp created_at
        timestamp updated_at
    }

    user_notifications {
        bigint id PK
        bigint user_id FK
        varchar type
        varchar title
        text message
        longtext data
        boolean is_read
        timestamp read_at
        timestamp created_at
        timestamp updated_at
    }

    visitor_logs {
        bigint id PK
        varchar ip_address
        text user_agent
        timestamp visited_at
        timestamp created_at
        timestamp updated_at
    }

    notifications {
        char id PK
        varchar type
        varchar notifiable_type
        bigint notifiable_id
        text data
        timestamp read_at
        timestamp created_at
        timestamp updated_at
    }

    personal_access_tokens {
        bigint id PK
        varchar tokenable_type
        bigint tokenable_id
        varchar name
        varchar token UK
        text abilities
        timestamp last_used_at
        timestamp expires_at
        timestamp created_at
        timestamp updated_at
    }

    password_reset_tokens {
        varchar email PK
        varchar token
        timestamp created_at
    }

    %% Relationships
    users ||--o{ posts : "creates"
    users ||--o{ posts : "rejects"
    users ||--o{ monuments : "creates"
    users ||--o{ monuments : "rejects"
    users ||--o{ contacts : "replies"
    users ||--o{ user_notifications : "receives"
    users ||--o{ personal_access_tokens : "has"

    posts ||--o{ post_translations : "has"
    monuments ||--o{ monument_translations : "has"
    monuments ||--o{ gallery : "contains"
    monuments ||--o{ feedbacks : "receives"

    %% Foreign Key Constraints
    posts }o--|| users : "created_by"
    posts }o--|| users : "rejected_by"
    monuments }o--|| users : "created_by"
    monuments }o--|| users : "rejected_by"
    contacts }o--|| users : "replied_by"
    user_notifications }o--|| users : "user_id"
    post_translations }o--|| posts : "post_id"
    monument_translations }o--|| monuments : "monument_id"
    gallery }o--|| monuments : "monument_id"
    feedbacks }o--|| monuments : "monument_id"
```

## Database Schema Overview

### Core Tables

#### 1. **users** - User Management
- Stores admin and moderator accounts
- Includes security questions for password recovery
- Role-based access control (admin/moderator)
- Status tracking (active/inactive)

#### 2. **posts** - Blog Posts
- Main content management for articles
- Approval workflow (draft/pending/approved/rejected)
- Soft deletes for data recovery
- Foreign keys to users for creator and reviewer

#### 3. **monuments** - Heritage Sites
- Core entity for heritage locations
- Geographic data (latitude/longitude)
- World wonder classification
- Approval workflow similar to posts

### Translation Tables

#### 4. **post_translations** - Multilingual Posts
- Supports multiple languages per post
- Separate title, description, and content
- Unique constraint on post_id + language

#### 5. **monument_translations** - Multilingual Monuments
- Multilingual support for monuments
- Includes location translations
- Unique constraint on monument_id + language

### Content Management

#### 6. **gallery** - Image Gallery
- Image management for monuments
- Links to monuments via foreign key
- Supports descriptions for each image

#### 7. **feedbacks** - User Feedback
- Public feedback system
- Rating system (1-5 stars)
- Status tracking (pending/approved/rejected)
- Links to specific monuments

#### 8. **contacts** - Contact Messages
- Contact form submissions
- Admin reply system
- Status tracking (new/read/replied)

### System Tables

#### 9. **site_settings** - Configuration
- Dynamic site configuration
- Key-value storage with metadata
- Categories and validation rules

#### 10. **user_notifications** - Custom Notifications
- Custom notification system
- Read/unread status tracking
- Rich data storage (JSON)

#### 11. **visitor_logs** - Analytics
- Visitor tracking by IP
- User agent logging
- Visit timestamp tracking

### Laravel Framework Tables

#### 12. **notifications** - Laravel Notifications
- Laravel's built-in notification system
- Polymorphic relationship support

#### 13. **personal_access_tokens** - API Authentication
- Sanctum token management
- API authentication tokens

#### 14. **password_reset_tokens** - Password Recovery
- Password reset functionality
- Email-based token system

## Key Features

### 🔐 **Security**
- Role-based access control
- API token authentication
- Password reset system
- Security questions

### 🌍 **Multilingual Support**
- Separate translation tables
- Language-specific content
- Flexible language codes

### 📊 **Content Management**
- Approval workflows
- Soft deletes
- Status tracking
- Rich content support

### 📈 **Analytics**
- Visitor tracking
- Feedback system
- Notification management

### 🔧 **Configuration**
- Dynamic settings
- Admin panel integration
- Flexible metadata storage


