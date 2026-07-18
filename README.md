# Short URL Assignment - README

## Project Overview
This is a URL Shortener assignment built with Laravel 12, Vue 3, Inertia.js, and TypeScript. The application allows users to invite Admin and Member users to a company and generate short URLs based on predefined business rules. Authentication and role-based authorization are implemented using a custom role field without relying on any external role or permission package.

## note: I used AI tools to assist with debugging, installing packages (such as DataTables and Toast notifications), and creating the README.md file

## Features
- **Company Management**: Create and manage companies
- **Role-Based Access**: SuperAdmin, Admin, and Member roles
- **User Invitations**: Invite users to companies
- **Short URL Generation**: Generate short URLs for companies
- **Responsive Dashboard**: DataTable-based company listing with pagination

## Tech Stack
- **Frontend**: Vue 3 with TypeScript
- **UI Framework**: Tailwind CSS
- **Data Table**: DataTables.net with Vue 3 integration
- **HTTP Client**: Axios
- **Routing**: Inertia.js
- **Notifications**: Vue Sonner (toast messages)

## Project Structure
```
e:\short-url-assignment\
├── resources/
│   └── js/
│       └── pages/
│           └── Dashboard.vue
├── components/
│   ├── InviteModal.vue
│   └── GenerateShortUrlModal.vue
└── README.md
```

## Installation

```bash
# Install dependencies
npm install

# or with yarn
yarn install
```

## Usage

### Dashboard Component
The main Dashboard page displays:
- List of companies in a DataTable
- Pagination controls
- Company management based on user role
- Action buttons (Invite Users, Generate Short URL)

### Key Features

#### Add Company (SuperAdmin only)
- Opens modal form
- Validates company name and email
- Displays validation errors
- Shows success/error toast notifications

#### Invite Users
- Available for non-member roles
- Opens invite modal with company details
- Limited to assigned companies

#### Generate Short URL
- Available for Admin and Member roles
- Opens modal for URL generation
- Handles short URL creation

## Component Props

### Dashboard Props
```typescript
{
    companies: {
        data: Array,           // Company list
        links: Array,          // Pagination links
        current_page: number,
        last_page: number,
        total: number
    }
}
```

## API Endpoints

- `POST /company/store` - Create new company
- Additional endpoints for invitations and short URL generation

## Development

### Build for Development
```bash
npm run dev
```

### Build for Production
```bash
npm run build
```

## Error Handling
- Validation errors from backend (422 status)
- Generic error handling with toast notifications
- Form field-level error display

## Browser Compatibility
- Modern browsers with Vue 3 support
- CSS Grid and Flexbox support required

## License
Proprietary - Short URL Assignment
