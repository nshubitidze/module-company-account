# Shubo_CompanyAccount — Architecture Document

**Module:** `Shubo_CompanyAccount`
**Composer:** `shubo/module-company-account`
**License:** MIT (free, open-source)
**Version:** 1.0.0

---

## 1. Module Directory Structure

```
Shubo/CompanyAccount/
├── registration.php
├── composer.json
├── etc/
│   ├── module.xml
│   ├── db_schema.xml
│   ├── db_schema_whitelist.json
│   ├── di.xml                          # DI preferences & plugins
│   ├── events.xml                      # Event observers
│   ├── acl.xml                         # Admin ACL resources
│   ├── email_templates.xml             # Email templates
│   ├── adminhtml/
│   │   ├── di.xml
│   │   ├── routes.xml
│   │   ├── menu.xml                    # Admin menu items
│   │   └── system.xml                  # System > Configuration
│   ├── frontend/
│   │   ├── di.xml
│   │   ├── routes.xml
│   │   └── sections.xml                # Customer section data
│   ├── webapi.xml                      # REST API routes
│   └── schema.graphqls                 # GraphQL schema
│
├── Api/
│   ├── Data/
│   │   ├── CompanyInterface.php
│   │   ├── CompanyAddressInterface.php
│   │   ├── CompanyTeamInterface.php
│   │   ├── CompanyRoleInterface.php
│   │   ├── CompanyUserInterface.php
│   │   └── CompanySearchResultsInterface.php
│   ├── CompanyRepositoryInterface.php
│   ├── CompanyAddressRepositoryInterface.php
│   ├── CompanyTeamRepositoryInterface.php
│   ├── CompanyRoleRepositoryInterface.php
│   ├── CompanyUserRepositoryInterface.php
│   └── CompanyManagementInterface.php   # High-level operations
│
├── Model/
│   ├── Company.php                      # Data model
│   ├── CompanyAddress.php
│   ├── CompanyTeam.php
│   ├── CompanyRole.php
│   ├── CompanyUser.php
│   ├── CompanyRepository.php
│   ├── CompanyAddressRepository.php
│   ├── CompanyTeamRepository.php
│   ├── CompanyRoleRepository.php
│   ├── CompanyUserRepository.php
│   ├── CompanyManagement.php            # Registration, approval, etc.
│   ├── Authorization/
│   │   └── CompanyPermission.php        # Permission checker
│   ├── ResourceModel/
│   │   ├── Company.php
│   │   ├── Company/Collection.php
│   │   ├── CompanyAddress.php
│   │   ├── CompanyAddress/Collection.php
│   │   ├── CompanyTeam.php
│   │   ├── CompanyTeam/Collection.php
│   │   ├── CompanyRole.php
│   │   ├── CompanyRole/Collection.php
│   │   ├── CompanyUser.php
│   │   └── CompanyUser/Collection.php
│   └── Config/
│       └── Source/
│           └── CompanyStatus.php        # Status option source
│
├── Controller/
│   ├── Adminhtml/
│   │   └── Company/
│   │       ├── Index.php                # Grid listing
│   │       ├── Edit.php                 # Edit form
│   │       ├── Save.php                 # Save action
│   │       ├── Delete.php               # Delete action
│   │       ├── Approve.php              # Approve action
│   │       ├── Reject.php               # Reject action
│   │       └── MassAction.php           # Mass approve/reject/delete
│   └── Account/
│       ├── Register.php                 # Company registration form
│       ├── RegisterPost.php             # Handle registration
│       ├── Dashboard.php                # Company dashboard
│       ├── Users/
│       │   ├── Index.php                # List company users
│       │   ├── Add.php                  # Invite user form
│       │   ├── AddPost.php              # Handle invite
│       │   ├── Edit.php                 # Edit user
│       │   ├── EditPost.php             # Save user changes
│       │   └── Delete.php               # Remove user
│       ├── Roles/
│       │   ├── Index.php                # List roles
│       │   ├── Edit.php                 # Edit role + permissions
│       │   ├── Save.php                 # Save role
│       │   └── Delete.php               # Delete role
│       ├── Teams/
│       │   ├── Index.php                # List teams
│       │   ├── Edit.php                 # Edit team
│       │   ├── Save.php                 # Save team
│       │   └── Delete.php               # Delete team
│       └── Profile/
│           ├── Index.php                # View company profile
│           └── Save.php                 # Edit company profile
│
├── Block/
│   └── Adminhtml/
│       └── Company/
│           └── Edit/
│               └── Buttons.php          # Save/Delete/Approve/Reject buttons
│
├── Ui/
│   └── Component/
│       ├── Listing/
│       │   └── Column/
│       │       ├── CompanyActions.php
│       │       └── Status.php
│       └── Form/
│           └── DataProvider/
│               └── CompanyDataProvider.php
│
├── ViewModel/
│   ├── CompanyRegistration.php          # Registration form data
│   ├── CompanyDashboard.php             # Dashboard data
│   ├── CompanyUsers.php                 # Users list data
│   ├── CompanyRoles.php                 # Roles management data
│   └── CompanyProfile.php               # Profile view/edit data
│
├── view/
│   ├── adminhtml/
│   │   ├── layout/
│   │   │   ├── shubo_company_index.xml
│   │   │   └── shubo_company_edit.xml
│   │   ├── ui_component/
│   │   │   ├── shubo_company_listing.xml
│   │   │   └── shubo_company_form.xml
│   │   └── templates/
│   │       └── company/
│   ├── frontend/
│   │   ├── layout/
│   │   │   ├── shubo_company_account_register.xml
│   │   │   ├── shubo_company_account_dashboard.xml
│   │   │   ├── shubo_company_account_users.xml
│   │   │   ├── shubo_company_account_roles.xml
│   │   │   ├── shubo_company_account_teams.xml
│   │   │   └── shubo_company_account_profile.xml
│   │   └── templates/
│   │       └── company/
│   │           ├── register.phtml                    # Registration form (Alpine.js)
│   │           ├── dashboard.phtml                   # Company dashboard
│   │           ├── users/list.phtml                  # Users management
│   │           ├── users/invite-modal.phtml           # Invite user modal
│   │           ├── roles/list.phtml                  # Roles management
│   │           ├── roles/edit-modal.phtml             # Edit role + permission tree
│   │           ├── teams/list.phtml                  # Teams tree view
│   │           ├── teams/edit-modal.phtml             # Edit team
│   │           └── profile/view.phtml                # Company profile
│   └── email/
│       ├── company_registration_pending.html
│       ├── company_approved.html
│       ├── company_rejected.html
│       ├── company_user_invited.html
│       └── company_admin_new_registration.html
│
├── Plugin/
│   ├── Customer/
│   │   └── AddCompanyDataToCustomerPlugin.php   # Add company info to customer session
│   └── Checkout/
│       └── AddCompanyInfoToOrderPlugin.php      # Attach company to orders (extension attr)
│
├── Observer/
│   ├── CustomerLoginObserver.php                # Load company context on login
│   └── CompanyStatusChangeObserver.php          # Email notifications on status change
│
├── Cron/
│   └── CleanupExpiredInvitations.php            # Clean up unaccepted invitations
│
├── Setup/
│   └── Patch/
│       └── Data/
│           └── CreateDefaultRoles.php           # Create "Company Admin" and "Buyer" default roles
│
├── Test/
│   ├── Unit/
│   │   ├── Model/
│   │   │   ├── CompanyRepositoryTest.php
│   │   │   ├── CompanyManagementTest.php
│   │   │   └── Authorization/
│   │   │       └── CompanyPermissionTest.php
│   │   └── ViewModel/
│   │       └── CompanyDashboardTest.php
│   └── Integration/
│       ├── Model/
│       │   └── CompanyRepositoryTest.php
│       └── Api/
│           ├── CompanyRestApiTest.php
│           └── CompanyGraphQlTest.php
│
├── i18n/
│   └── en_US.csv
│
└── CHANGELOG.md
```

---

## 2. Database Schema

### Tables

| Table | Purpose | Key Relations |
|-------|---------|--------------|
| `shubo_company` | Company entities | → `customer_entity` (admin), self-referencing (parent) |
| `shubo_company_address` | Company addresses | → `shubo_company` |
| `shubo_company_team` | Teams/divisions | → `shubo_company`, self-referencing (parent) |
| `shubo_company_role` | Custom roles | → `shubo_company` |
| `shubo_company_role_permission` | Role permissions | → `shubo_company_role` |
| `shubo_company_user` | Customer-Company link | → `shubo_company`, `customer_entity`, role, team |

### Company Statuses
- `0` = Pending Approval
- `1` = Approved (Active)
- `2` = Rejected
- `3` = Blocked

### Key Constraints
- One customer can belong to only one company (`customer_id` is UNIQUE on `shubo_company_user`)
- Deleting a company cascades to addresses, teams, roles, permissions, and user assignments
- Deleting a customer cascades their company user record
- Roles/teams use SET NULL on delete so users aren't lost, just unassigned

See `db_schema.xml` for complete schema definition.

---

## 3. Service Contracts (API Interfaces)

### CompanyInterface (Data)
```php
namespace Shubo\CompanyAccount\Api\Data;

interface CompanyInterface
{
    const ENTITY_ID = 'entity_id';
    const COMPANY_NAME = 'company_name';
    const LEGAL_NAME = 'legal_name';
    const COMPANY_EMAIL = 'company_email';
    const VAT_TAX_ID = 'vat_tax_id';
    const RESELLER_ID = 'reseller_id';
    const PHONE = 'phone';
    const WEBSITE = 'website';
    const STATUS = 'status';
    const ADMIN_CUSTOMER_ID = 'admin_customer_id';
    const SALES_REPRESENTATIVE_ID = 'sales_representative_id';
    const PARENT_ID = 'parent_id';

    // Standard getters/setters for all columns
    public function getEntityId(): ?int;
    public function getCompanyName(): string;
    public function setCompanyName(string $name): self;
    public function getStatus(): int;
    public function setStatus(int $status): self;
    // ... all other getters/setters
}
```

### CompanyRepositoryInterface
```php
namespace Shubo\CompanyAccount\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Shubo\CompanyAccount\Api\Data\CompanyInterface;
use Shubo\CompanyAccount\Api\Data\CompanySearchResultsInterface;

interface CompanyRepositoryInterface
{
    /** @throws \Magento\Framework\Exception\NoSuchEntityException */
    public function getById(int $companyId): CompanyInterface;

    public function save(CompanyInterface $company): CompanyInterface;

    /** @throws \Magento\Framework\Exception\CouldNotDeleteException */
    public function delete(CompanyInterface $company): bool;

    public function getList(SearchCriteriaInterface $criteria): CompanySearchResultsInterface;

    /** Get company by customer ID */
    public function getByCustomerId(int $customerId): CompanyInterface;
}
```

### CompanyManagementInterface (High-Level Operations)
```php
namespace Shubo\CompanyAccount\Api;

interface CompanyManagementInterface
{
    /**
     * Register a new company with admin user
     * Creates company + customer account + company_user link
     * Sets status to Pending
     * Dispatches shubo_company_register_after event
     */
    public function register(array $companyData, array $adminData): Data\CompanyInterface;

    /**
     * Approve a pending company
     * Sets status to Approved, sends approval email
     * Dispatches shubo_company_approve_after event
     */
    public function approve(int $companyId): Data\CompanyInterface;

    /**
     * Reject a pending company
     * Dispatches shubo_company_reject_after event
     */
    public function reject(int $companyId, string $reason): Data\CompanyInterface;

    /**
     * Invite a user to the company
     * Creates customer if not exists, creates company_user link
     * Sends invitation email
     */
    public function inviteUser(int $companyId, array $userData, ?int $roleId, ?int $teamId): Data\CompanyUserInterface;

    /**
     * Check if a customer belongs to any company
     */
    public function isCompanyUser(int $customerId): bool;

    /**
     * Get company for a customer
     */
    public function getCompanyByCustomerId(int $customerId): ?Data\CompanyInterface;
}
```

Similar repository interfaces exist for Address, Team, Role, User (all follow the same pattern).

---

## 4. GraphQL Schema

```graphql
# === Queries ===

type Query {
    # Customer-facing: get current customer's company
    shuboCompany: ShuboCompany @resolver(class: "Shubo\\CompanyAccount\\Model\\Resolver\\Company") @cache(cacheable: false)

    # Customer-facing: list users in my company
    shuboCompanyUsers(
        pageSize: Int = 20
        currentPage: Int = 1
    ): ShuboCompanyUserList @resolver(class: "Shubo\\CompanyAccount\\Model\\Resolver\\CompanyUsers") @cache(cacheable: false)

    # Customer-facing: list roles in my company
    shuboCompanyRoles: [ShuboCompanyRole] @resolver(class: "Shubo\\CompanyAccount\\Model\\Resolver\\CompanyRoles") @cache(cacheable: false)

    # Customer-facing: list teams in my company
    shuboCompanyTeams: [ShuboCompanyTeam] @resolver(class: "Shubo\\CompanyAccount\\Model\\Resolver\\CompanyTeams") @cache(cacheable: false)

    # Customer-facing: check my permissions
    shuboMyPermissions: [String] @resolver(class: "Shubo\\CompanyAccount\\Model\\Resolver\\MyPermissions") @cache(cacheable: false)
}

# === Mutations ===

type Mutation {
    # Guest: register a new company
    shuboRegisterCompany(input: ShuboCompanyRegistrationInput!): ShuboCompanyRegistrationOutput
        @resolver(class: "Shubo\\CompanyAccount\\Model\\Resolver\\RegisterCompany")

    # Company Admin: update company profile
    shuboUpdateCompanyProfile(input: ShuboCompanyProfileInput!): ShuboCompany
        @resolver(class: "Shubo\\CompanyAccount\\Model\\Resolver\\UpdateCompanyProfile")

    # Company Admin: invite a user
    shuboInviteCompanyUser(input: ShuboInviteUserInput!): ShuboCompanyUser
        @resolver(class: "Shubo\\CompanyAccount\\Model\\Resolver\\InviteUser")

    # Company Admin: update a user's role/team/status
    shuboUpdateCompanyUser(input: ShuboUpdateUserInput!): ShuboCompanyUser
        @resolver(class: "Shubo\\CompanyAccount\\Model\\Resolver\\UpdateUser")

    # Company Admin: remove a user
    shuboRemoveCompanyUser(userId: Int!): Boolean
        @resolver(class: "Shubo\\CompanyAccount\\Model\\Resolver\\RemoveUser")

    # Company Admin: create/update role
    shuboSaveCompanyRole(input: ShuboRoleInput!): ShuboCompanyRole
        @resolver(class: "Shubo\\CompanyAccount\\Model\\Resolver\\SaveRole")

    # Company Admin: delete role
    shuboDeleteCompanyRole(roleId: Int!): Boolean
        @resolver(class: "Shubo\\CompanyAccount\\Model\\Resolver\\DeleteRole")

    # Company Admin: create/update team
    shuboSaveCompanyTeam(input: ShuboTeamInput!): ShuboCompanyTeam
        @resolver(class: "Shubo\\CompanyAccount\\Model\\Resolver\\SaveTeam")

    # Company Admin: delete team
    shuboDeleteCompanyTeam(teamId: Int!): Boolean
        @resolver(class: "Shubo\\CompanyAccount\\Model\\Resolver\\DeleteTeam")
}

# === Types ===

type ShuboCompany {
    entity_id: Int
    company_name: String
    legal_name: String
    company_email: String
    vat_tax_id: String
    reseller_id: String
    phone: String
    website: String
    status: Int
    status_label: String
    addresses: [ShuboCompanyAddress]
    admin: ShuboCompanyUser
    user_count: Int
}

type ShuboCompanyAddress {
    address_id: Int
    type: String
    street_line1: String
    street_line2: String
    city: String
    region: String
    postcode: String
    country_id: String
    telephone: String
    is_default: Boolean
}

type ShuboCompanyUser {
    user_id: Int
    customer_id: Int
    firstname: String
    lastname: String
    email: String
    job_title: String
    phone: String
    role: ShuboCompanyRole
    team: ShuboCompanyTeam
    status: Int
    is_company_admin: Boolean
}

type ShuboCompanyUserList {
    items: [ShuboCompanyUser]
    total_count: Int
    page_info: SearchResultPageInfo
}

type ShuboCompanyRole {
    role_id: Int
    role_name: String
    description: String
    is_default: Boolean
    permissions: [String]
    user_count: Int
}

type ShuboCompanyTeam {
    team_id: Int
    name: String
    description: String
    parent_team_id: Int
    children: [ShuboCompanyTeam]
    user_count: Int
}

# === Inputs ===

input ShuboCompanyRegistrationInput {
    company_name: String!
    company_email: String!
    legal_name: String
    vat_tax_id: String
    phone: String
    website: String
    # Admin user details
    admin_firstname: String!
    admin_lastname: String!
    admin_email: String!
    admin_password: String!
    # Address
    street_line1: String!
    street_line2: String
    city: String!
    region: String
    region_id: Int
    postcode: String!
    country_id: String!
}

type ShuboCompanyRegistrationOutput {
    company: ShuboCompany
    message: String
}

input ShuboCompanyProfileInput {
    company_name: String
    legal_name: String
    vat_tax_id: String
    phone: String
    website: String
}

input ShuboInviteUserInput {
    email: String!
    firstname: String!
    lastname: String!
    role_id: Int
    team_id: Int
    job_title: String
}

input ShuboUpdateUserInput {
    user_id: Int!
    role_id: Int
    team_id: Int
    job_title: String
    status: Int
}

input ShuboRoleInput {
    role_id: Int          # Null for create, ID for update
    role_name: String!
    description: String
    permissions: [String!]!
}

input ShuboTeamInput {
    team_id: Int          # Null for create, ID for update
    name: String!
    description: String
    parent_team_id: Int
}
```

---

## 5. Admin UI Plan

### Menu Structure
```
Shubo (top-level menu)
└── B2B Companies
    ├── All Companies (grid listing)
    └── Configuration (system config)
```

### Company Grid (`shubo_company_listing.xml`)
Columns: ID, Company Name, Email, Admin User, Status, Store View, Created At, Actions
Filters: Status, Store View, Date Range
Mass Actions: Approve, Reject, Delete
Row Actions: Edit, Approve, Reject, Delete

### Company Edit Form (`shubo_company_form.xml`)
Tabs:
1. **General** — Name, legal name, email, VAT, reseller ID, phone, website
2. **Address** — Street, city, region, postcode, country
3. **Admin User** — Shows linked customer, link to customer edit
4. **Users** — Grid of all company users with roles/teams
5. **Roles** — Inline role management
6. **Teams** — Tree view of team hierarchy
7. **Activity** — Status history log

### System Configuration (`system.xml`)
Path: `Stores > Configuration > Shubo > Company Accounts`
- Enable/Disable module
- Enable/Disable registration
- Auto-approve companies (yes/no)
- Default role for new company admins
- Default role for invited users
- Registration notification email (admin)
- Approval/Rejection email templates
- Invitation email template
- Allowed registration customer groups

---

## 6. Frontend Plan (Hyva-Native)

### Routes
| URL | Controller | Page |
|-----|-----------|------|
| `/company/account/register` | Account\Register | Registration form |
| `/company/account/dashboard` | Account\Dashboard | Company dashboard |
| `/company/account/users` | Account\Users\Index | User management |
| `/company/account/roles` | Account\Roles\Index | Role management |
| `/company/account/teams` | Account\Teams\Index | Team management |
| `/company/account/profile` | Account\Profile\Index | Company profile |

### Registration Page (`register.phtml`)
Alpine.js multi-step form:
- Step 1: Company details (name, email, VAT, phone)
- Step 2: Address
- Step 3: Admin user account (name, email, password)
- Step 4: Review & Submit
- Success: "Your registration is pending approval" message

### Company Dashboard (`dashboard.phtml`)
Alpine.js dashboard with:
- Company info card
- Quick stats (users count, pending invitations, recent activity)
- Quick actions (invite user, manage roles)
- Navigation to sub-pages

### Users Page (`users/list.phtml`)
Alpine.js data table with:
- User list (name, email, role, team, status)
- Invite modal (Alpine.js + GraphQL mutation)
- Edit inline or modal
- Remove with confirmation
- Filter by role/team/status

### Roles Page (`roles/list.phtml`)
Alpine.js with:
- Role cards/list
- Create/edit modal with permission tree (checkbox tree)
- Delete with user reassignment prompt

### Teams Page (`teams/list.phtml`)
Alpine.js tree component:
- Hierarchical team tree (drag-and-drop optional in v2)
- Create/edit team modal
- Assign users to teams

All pages are added to the customer account navigation sidebar.

---

## 7. ACL Resources

```xml
<config>
  <acl>
    <resources>
      <resource id="Magento_Backend::admin">
        <resource id="Shubo_CompanyAccount::company" title="Shubo B2B Companies" sortOrder="30">
          <resource id="Shubo_CompanyAccount::company_manage" title="Manage Companies" sortOrder="10"/>
          <resource id="Shubo_CompanyAccount::company_approve" title="Approve/Reject Companies" sortOrder="20"/>
          <resource id="Shubo_CompanyAccount::company_delete" title="Delete Companies" sortOrder="30"/>
          <resource id="Shubo_CompanyAccount::config" title="Configuration" sortOrder="40"/>
        </resource>
      </resource>
    </resources>
  </acl>
</config>
```

### Frontend (Company-Level) Permissions
These are stored in `shubo_company_role_permission` and checked by `CompanyPermission`:

```
Shubo_CompanyAccount::view_dashboard
Shubo_CompanyAccount::manage_users
Shubo_CompanyAccount::manage_roles
Shubo_CompanyAccount::manage_teams
Shubo_CompanyAccount::edit_profile
Shubo_CompanyAccount::view_orders        # For future Order module
Shubo_CompanyAccount::manage_quotes       # For future RFQ module
Shubo_CompanyAccount::manage_purchase_orders  # For future PO module
Shubo_CompanyAccount::view_credit         # For future Credit module
```

---

## 8. Extension Points (for other Shubo modules)

### Events Dispatched
| Event | When | Data |
|-------|------|------|
| `shubo_company_register_after` | After registration | company, admin_customer |
| `shubo_company_approve_after` | After approval | company |
| `shubo_company_reject_after` | After rejection | company, reason |
| `shubo_company_user_add_after` | After user added | company_user, company |
| `shubo_company_user_remove_after` | After user removed | customer_id, company_id |
| `shubo_company_status_change` | Any status change | company, old_status, new_status |

### Plugin Points
- `CompanyRepositoryInterface::save` — before/after company save
- `CompanyManagementInterface::register` — before/after registration
- `CompanyManagementInterface::inviteUser` — before/after invitation
- `CompanyPermission::isAllowed` — modify permission check logic

### Extension Attributes
- `customer` entity gets `company_data` extension attribute (company info for logged-in user)
- `order` entity gets `company_id` extension attribute (link orders to companies)

### Service Contracts
Other modules depend on `Api\` interfaces, never on `Model\` classes directly. This ensures the B2B suite modules are loosely coupled.

---

## 9. Dependencies

### Magento Core Dependencies
- `Magento_Customer` — customer entity, customer session
- `Magento_Store` — multi-store support
- `Magento_Email` — transactional emails
- `Magento_Ui` — admin UI components
- `Magento_Backend` — admin controllers, ACL
- `Magento_Authorization` — ACL framework
- `Magento_Directory` — country/region data

### No Dependencies On
- Any other Shubo module (this is the foundation)
- Magento_Checkout, Magento_Sales (optional plugins, soft dependency)

---

## 10. Estimated Complexity

| Component | Effort |
|-----------|--------|
| Database schema + models + repositories | 1 week |
| Service contracts + management class | 1 week |
| Admin UI (grid + form + config) | 1 week |
| Frontend Hyva (registration + dashboard + users + roles + teams) | 2 weeks |
| GraphQL API (all queries + mutations) | 1 week |
| REST API (webapi.xml) | 3 days |
| Email templates | 2 days |
| Plugins (customer session, order) | 2 days |
| Unit + Integration tests | 1 week |
| Documentation | 3 days |
| **Total** | **~8 weeks** |
