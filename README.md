# Shubo Company Account for Magento 2

**Free, open-source B2B Company Accounts for Magento 2 Open Source.**

Create company hierarchies, manage users with roles and permissions, and enable B2B workflows — all without Adobe Commerce.

## Features

- **Company Registration** — Multi-step storefront registration with admin approval workflow
- **User Management** — Invite employees, assign roles and teams
- **Roles & Permissions** — Granular permission system for company-level access control
- **Team Hierarchy** — Organize users into departments and divisions
- **Admin Panel** — Full company management grid with approve/reject/mass actions
- **REST API** — Complete CRUD + management endpoints
- **GraphQL** — Full query and mutation support for headless/Hyva storefronts
- **Email Notifications** — Registration pending, approved, rejected, user invitation templates
- **Hyva Compatible** — Built with Alpine.js, fully Hyva-native frontend

## Requirements

- Magento 2.4.6+ (Open Source or Commerce)
- PHP 8.1+

## Installation

```bash
composer require shubo/module-company-account
bin/magento module:enable Shubo_CompanyAccount
bin/magento setup:upgrade
bin/magento cache:flush
```

## Configuration

Navigate to **Stores > Configuration > Shubo > Company Accounts** to:

- Enable/disable the module
- Enable/disable storefront registration
- Configure auto-approve for new companies
- Set up email notification templates
- Configure admin notification email

## Usage

### Storefront
- Visit `/company/account/register` to register a new company
- Company dashboard at `/company/account/dashboard`
- Manage users, roles, teams from the customer account area

### Admin
- **Shubo > B2B Companies** — manage all companies
- Approve, reject, or block companies
- View company users, roles, and teams

### REST API
```
POST   /V1/shubo/company/register     — Register new company (guest)
GET    /V1/shubo/company/:id          — Get company by ID
POST   /V1/shubo/company/:id/approve  — Approve company
POST   /V1/shubo/company/:id/reject   — Reject company
POST   /V1/shubo/company/:id/invite   — Invite user to company
GET    /V1/shubo/companies            — List companies
```

### GraphQL
```graphql
query { shuboCompany { company_name status_label } }
mutation { shuboRegisterCompany(input: { ... }) { company { entity_id } } }
```

## Part of the Shubo B2B Suite

This is the foundation module for the Shubo B2B Suite. Premium modules that build on Company Account:

- **Request for Quote** — RFQ workflows for B2B negotiations
- **Shared Catalogs** — Company-specific pricing and product visibility
- **Purchase Orders** — Approval workflows for company purchases
- **Credit Limits** — Payment on account with credit management

## License

MIT License — free for personal and commercial use.

## Support

- [GitHub Issues](https://github.com/nshubitidze/module-company-account/issues)
- Email: nika.shubitidze.2727@gmail.com
