# Changelog

All notable changes to shubo/module-company-account will be documented in this file.

## [1.0.0] - 2026-04-04

### Added
- Company registration with multi-step storefront form (Hyva/Alpine.js)
- Admin approval workflow (approve, reject, block)
- Company user management (invite, assign roles/teams, remove)
- Role-based permission system with granular access control
- Team hierarchy (nested departments/divisions)
- Full admin panel (grid, edit form, mass actions, system config)
- REST API for all entities and management operations
- GraphQL queries and mutations for headless storefronts
- Email templates (registration pending, approved, rejected, invitation, admin notification)
- Customer extension attributes (company_id, company_name)
- Order extension attributes (company_id)
- Customer login observer (loads company context into session)
- Company status change observer (sends notification emails)
- Cron job for expired invitation cleanup
- Internationalization support (en_US)
- db_schema_whitelist.json for schema upgrades
