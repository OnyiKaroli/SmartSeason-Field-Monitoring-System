# SmartSeason Field Monitoring System - Antigravity Master Build Prompt

Build this project systematically as a professional internship assessment submission. The goal is not just to make it work, but to clearly demonstrate system design ability, business logic thinking, usable UI, clean structure, and disciplined delivery.

This is for a Full Stack Developer Technical Assessment called **SmartSeason Field Monitoring System**. The system must help track crop progress across multiple fields during a growing season. The required capabilities include:
- authentication
- two user roles: Admin (Coordinator) and Field Agent
- field management
- assigning fields to field agents
- field stage updates
- notes and observations
- computed field status
- dashboards for Admin and Field Agent
- README with setup instructions, design decisions, assumptions, and demo credentials
- repository-ready implementation with clean commits and clear progression

The brief emphasizes:
- clean system design
- core business logic
- usable interface
- clear separation of concerns
- simplicity over over-engineering
- clarity and reliability over excessive completeness :contentReference[oaicite:0]{index=0}

## High-Level Objective

Develop an internship-grade web application that:
1. fulfills all required assessment requirements
2. adds a few high-value extras without becoming over-engineered
3. is built in a phased, commit-friendly way
4. is tested feature by feature before each commit
5. includes a polished README suitable for submission
6. is easy for reviewers to run and evaluate

## Product Positioning

This system should communicate:
- ability to translate business requirements into technical structure
- ability to implement secure authentication and role-based access
- ability to define and explain business rules
- ability to build clear dashboards and workflows
- ability to structure a repo and commit history professionally

Do not build this like a hackathon mess. Build it like a junior engineer with strong discipline and good instincts.

## Development Style

Develop in small, testable phases. After every phase:
1. implement the feature fully
2. test the feature
3. verify role permissions and UI behavior if relevant
4. clean up any issues
5. prepare a meaningful git commit that tells the story of progress

The commit history must read like a narrative of the system being designed and matured step by step.

## Stack Guidance

Use a practical stack that allows fast, clean delivery.

Preferred stack:
- Laravel
- MySQL
- Blade templates with clean reusable components
- Tailwind CSS for styling
- Laravel authentication
- Eloquent models, migrations, seeders, factories, policies, form requests, controllers, services where appropriate

If a frontend enhancement is needed, keep it lightweight. Do not introduce unnecessary complexity.

## Core Functional Requirements

Implement the following exactly and cleanly:

### 1. Authentication and Access Control
Support two roles:
- Admin
- Field Agent

Requirements:
- secure login
- role-based authorization
- users only access what is relevant to them
- Admin can access all fields and management actions
- Field Agent can only access assigned fields and permitted update actions

### 2. Field Management
Admin should be able to:
- create fields
- edit fields
- view fields
- assign fields to field agents

Each field must contain at minimum:
- name
- crop type
- planting date
- current stage

### 3. Field Updates
Field Agents should be able to:
- update the stage of a field
- add notes or observations

Admins should be able to:
- view all fields
- monitor updates across agents

### 4. Field Stages
Implement this lifecycle:
- Planted
- Growing
- Ready
- Harvested

You may extend internally if needed, but the visible system must support and respect these stages clearly. :contentReference[oaicite:1]{index=1}

### 5. Field Status Logic
Each field must have a computed status:
- Active
- At Risk
- Completed

Implement reasonable and explainable logic.

Recommended logic:
- Completed: current stage is Harvested
- At Risk: field is not harvested and either:
  - has not received an update in more than a defined number of days, or
  - has remained in a stage longer than expected based on planting date and last update
- Active: all other non-harvested fields

This logic must be consistent and transparent. Also surface the reason for the computed status in the UI where useful.

### 6. Dashboard
Provide dashboards for both roles.

Admin dashboard should include:
- total fields
- status breakdown
- stage breakdown
- recently updated fields
- fields needing attention
- agent activity summary

Field Agent dashboard should include:
- assigned fields count
- fields needing updates
- recent updates submitted
- quick access to assigned fields

## High-Value Extras to Include

Add only these extras because they increase value without over-engineering:

### A. Field Activity Timeline
Each field detail page should show:
- all updates in chronological order
- who updated it
- stage change
- notes
- timestamp

### B. Needs Attention Indicator
Add a derived attention flag for fields that need action soon.
Examples:
- no update in 7 or more days
- stage has remained unchanged too long
- field currently at risk

### C. Status Explanation
Where a field status is shown, include or make accessible a short explanation.
Examples:
- Active: updated 2 days ago
- At Risk: no update in 9 days
- Completed: harvested on 2026-04-18

### D. Filtering and Search
Allow field filtering by:
- status
- stage
- crop type
- assigned agent

### E. Realistic Seed Data
Seed the database with realistic demo data:
- admin user
- at least 2 or 3 field agents
- multiple fields across different stages
- some fields at risk
- some recently updated
- one or more completed fields

## Architecture Expectations

Keep the codebase organized and clear.

Recommended domain structure:

### Models
- User
- Field
- FieldUpdate

### Possible Services
- FieldStatusService
- DashboardSummaryService

### Policies / Authorization
- FieldPolicy
- FieldUpdatePolicy

### Form Requests
- StoreFieldRequest
- UpdateFieldRequest
- StoreFieldUpdateRequest
- AssignFieldRequest

### Controllers
- Auth controllers as appropriate
- Admin dashboard controller
- Agent dashboard controller
- Field controller
- Field assignment controller
- Field update controller

### Views
- auth pages
- admin dashboard
- agent dashboard
- fields index
- field create/edit form
- field detail page
- field update form

Use reusable layout and components. Keep view structure readable.

## Database Design

Design a clean relational schema.

### users
- id
- name
- email
- password
- role
- timestamps

### fields
- id
- name
- crop_type
- planting_date
- current_stage
- assigned_agent_id nullable
- created_by nullable
- timestamps

### field_updates
- id
- field_id
- updated_by
- previous_stage nullable
- new_stage
- note nullable
- observed_at
- timestamps

You may add supportive columns such as:
- last_updated_at
- status cache only if justified, but computed status should remain explainable and trustworthy

Prefer keeping status computed in domain logic rather than manually edited.

## UX/UI Requirements

Keep the UI simple, clean, and professional.

### General
- responsive layout
- clear spacing and typography
- status badges
- stage badges
- validation messages
- success feedback
- empty states for tables and dashboards

### Admin UX
- easy field management
- clear assignment controls
- visibility across all fields
- concise dashboard metrics

### Agent UX
- simplified navigation
- only assigned fields shown
- fast update flow
- recent activity visible

Do not over-design. Do not make it flashy. Make it usable and polished.

## Validation and Error Handling

Implement proper validation and user-friendly errors.

Examples:
- planting date cannot be in the future
- field name required
- crop type required
- stage must be valid
- assignment must target a field agent role
- agent cannot update unassigned fields

Handle unauthorized access correctly.

## Security and Authorization

This matters. Show discipline.
- protect routes by auth
- enforce role checks server-side
- do not rely only on hidden UI controls
- enforce that field agents only see or update relevant fields
- validate all submitted data

## Testing Requirements

Testing is mandatory for each feature phase. Before each commit:
- confirm main happy-path behavior works
- verify access control
- verify invalid input handling where relevant
- verify dashboard values are sensible

At minimum, include:
- feature tests for authentication and role access
- field CRUD tests
- field assignment tests
- field update tests
- computed status tests
- dashboard summary tests where practical

Where full automated coverage is too heavy, still create enough automated tests to demonstrate engineering discipline.

### Required Testing Mindset
For every feature:
1. implement
2. run tests
3. manually verify the UI flow
4. fix issues
5. commit only after the feature is stable

## Commit Story Requirement

The repo must tell a story through commits. Create clean, meaningful commits after each stable feature. Avoid giant mixed commits.

Use a sequence similar to this:

1. chore: initialize Laravel project and base configuration
2. feat: set up authentication and user roles
3. feat: add field management schema models and migrations
4. feat: implement admin field CRUD
5. feat: add field assignment workflow for agents
6. feat: implement agent field updates and notes
7. feat: add computed field status service
8. feat: build admin dashboard summaries
9. feat: build field agent dashboard
10. feat: add field activity timeline and attention indicators
11. feat: add filtering search and usability improvements
12. test: add feature coverage for fields updates and access control
13. docs: write README setup assumptions and design decisions
14. chore: polish demo seed data and submission readiness

Adjust as needed, but keep the structure disciplined.

## Phase-by-Phase Build Plan

Build the system in the following order.

### Phase 1: Project Setup
- initialize project
- configure environment
- install auth scaffolding if needed
- set up Tailwind and base layout
- define role strategy
- create base seeders

Deliverable:
- app boots
- auth works
- roles exist
- base layout exists

Test before commit:
- login works
- protected routes redirect correctly
- role values seed correctly

### Phase 2: Users and Access Rules
- implement Admin and Field Agent roles
- add middleware / policy enforcement
- create sample demo users

Deliverable:
- role-restricted navigation
- route protection in place

Test before commit:
- Admin can access admin pages
- Agent cannot access admin-only pages
- unauthorized access returns correct response or redirect

### Phase 3: Field Domain
- create fields migration
- create Field model
- implement field CRUD for Admin
- create fields index and form views

Deliverable:
- Admin can create, edit, and view fields

Test before commit:
- field creation works
- validation works
- field editing works
- agents cannot perform admin CRUD

### Phase 4: Assignment Workflow
- allow Admin to assign a field to a field agent
- show assignment clearly in field lists and details

Deliverable:
- fields can be assigned and reassigned

Test before commit:
- only Admin can assign
- only users with Field Agent role can be assigned
- assigned agent appears correctly in UI

### Phase 5: Field Updates
- create field_updates table and model
- allow Field Agents to submit stage updates and notes
- record timestamps and updater
- update field current stage accordingly

Deliverable:
- agent can update assigned field stage
- notes are stored
- update history is retained

Test before commit:
- assigned agent can update assigned field
- unassigned agent cannot update other fields
- stage changes persist
- notes persist
- update history records correctly

### Phase 6: Computed Status Logic
- create FieldStatusService
- compute Active / At Risk / Completed
- define clear rules
- expose status and status reason in UI

Deliverable:
- status is visible and consistent across pages and dashboard

Test before commit:
- harvested field becomes Completed
- stale field becomes At Risk
- recently updated non-harvested field is Active

### Phase 7: Dashboards
- build Admin dashboard
- build Field Agent dashboard
- include required summaries
- include useful insights

Deliverable:
- dashboards are meaningful and readable

Test before commit:
- counts are correct
- Agent sees only own summary
- Admin sees global summary

### Phase 8: High-Value Extras
- field activity timeline
- needs attention indicators
- filtering and search
- improved UX messages and badges

Deliverable:
- polished user experience
- professional review flow

Test before commit:
- filters work
- timeline order is correct
- attention indicators reflect logic

### Phase 9: Seed Data and Demo Readiness
- create polished demo data
- ensure dashboards are populated
- prepare demo accounts

Deliverable:
- project is reviewer-ready immediately after setup

Test before commit:
- fresh seed produces good sample environment
- dashboards and lists are meaningful on first run

### Phase 10: README and Final Polish
Write a strong README that includes:
- project overview
- chosen stack and why
- setup instructions
- environment setup
- database migration and seeding steps
- demo credentials
- design decisions
- assumptions made
- explanation of computed field status logic
- trade-offs
- test instructions
- optional screenshots if available

The README must directly satisfy the submission expectations from the brief. :contentReference[oaicite:2]{index=2}

## Unit Testing and Feature Testing Requirements

Testing must be integrated into development from the beginning. Do not treat testing as a final cleanup task.

### Testing Philosophy
Use the right kind of test for the right kind of problem:

- Unit tests:
  - test isolated business logic
  - should be fast and focused
  - should not depend on full UI flows
  - use these for domain rules and pure logic

- Feature tests:
  - test end-to-end application behavior
  - cover routes, controllers, permissions, forms, and database interactions
  - use these for user workflows and access control

### When to Introduce Tests
Introduce tests as soon as a feature has meaningful logic or behavior worth protecting.

Do not wait until the whole system is complete.

Expected pattern for each phase:
1. implement the feature
2. add or update relevant tests
3. run tests
4. fix failing behavior
5. only then prepare the commit

### Unit Tests Required
Add unit tests for critical business logic, especially the logic that demonstrates reasoning ability.

At minimum, include unit tests for:

#### 1. Field Status Logic
Create strong unit test coverage for the computed field status.

Scenarios must include:
- harvested field returns Completed
- recently updated non-harvested field returns Active
- stale field with no recent updates returns At Risk
- field stuck in same stage too long returns At Risk if such rule is implemented

This is one of the most important test targets in the system.

#### 2. Needs Attention Logic
If a separate needs-attention rule or helper is implemented, add unit tests for it.

Scenarios should include:
- field with old update is flagged
- recently updated field is not flagged
- completed field is not flagged unless explicitly intended

#### 3. Any Extracted Business Rule Helpers
If helper classes, services, or rule evaluators are introduced for:
- assignment eligibility
- stage progression
- dashboard summary calculations
- date-based risk checks

add focused unit tests for them.

### Feature Tests Required
Add feature tests for key user flows and permissions.

At minimum, include feature tests for:
- authentication works
- admin can access admin dashboard
- field agent cannot access admin-only routes
- admin can create a field
- admin can assign a field to a field agent
- assigned field agent can submit a field update
- unassigned field agent cannot update another agent's field
- dashboard data is scoped correctly by role where practical

### Test Quality Rules
All tests should:
- have clear names
- describe behavior, not implementation details
- cover both expected and restricted behavior where relevant
- remain readable and easy to maintain

Do not create shallow tests that only increase test count without protecting important logic.

### Test File Organization
Organize tests clearly, for example:
- tests/Unit/Services/FieldStatusServiceTest.php
- tests/Unit/Services/NeedsAttentionServiceTest.php
- tests/Feature/Auth/AuthenticationTest.php
- tests/Feature/Fields/FieldManagementTest.php
- tests/Feature/Fields/FieldAssignmentTest.php
- tests/Feature/Fields/FieldUpdateTest.php
- tests/Feature/Dashboard/AdminDashboardTest.php

Adjust structure as needed, but keep it consistent.

### Per-Phase Testing Requirement
For every development phase, after implementation Antigravity must also provide:
- the tests added for that phase
- a short explanation of what they verify
- the command to run the tests
- expected pass criteria
- any edge cases covered

Do not proceed to the next phase until the current phase is implemented and relevant tests are added.

### Commit Discipline for Testing
Testing should appear naturally in commit history.

Examples:
- feat: implement admin field CRUD with validation
- test: add feature coverage for field creation and access restrictions
- feat: add computed field status logic
- test: add unit coverage for field status scenarios

Testing-related commits should make the repo history show disciplined engineering practice.

### Final Submission Readiness
Before finalizing the project, ensure:
- critical business logic has unit test coverage
- major user flows have feature test coverage
- tests run successfully on a fresh setup
- README includes instructions for running tests
- README briefly mentions what kinds of logic are covered by tests

## README Writing Instructions

Generate a professional README with the following structure:

1. Project Title
2. Overview
3. Features
4. Tech Stack
5. System Roles
6. Field Status Logic
7. Design Decisions
8. Assumptions
9. Setup Instructions
10. Database Migration and Seeding
11. Running the Application
12. Running Tests
13. Demo Credentials
14. Project Structure Summary
15. Trade-Offs and Future Improvements

Make the README clear and concise, not bloated. It should help a reviewer quickly understand:
- what was built
- how to run it
- why decisions were made

## Demo Credentials Requirement

Create clear demo credentials such as:
- Admin: admin@smartseason.test / password
- Agent: agent1@smartseason.test / password
- Agent: agent2@smartseason.test / password

Use secure seeded credentials suitable for review. Mention them in README.

## Quality Bar

The final system should feel:
- simple
- coherent
- reliable
- easy to demo
- easy to review

Do not over-engineer.
Do not add advanced infrastructure that does not materially improve the assessment.
Do not add websockets, notifications, maps, uploads, or unnecessary abstractions unless there is a very strong reason.

## Final Instruction to Antigravity

Work through this project systematically, phase by phase, without skipping validation. After each phase:
- verify the feature works
- verify permissions
- verify UI flow
- suggest a clean git commit message
- move to the next phase only after stability is confirmed

Whenever implementing a new feature:
- preserve clean architecture
- keep naming consistent
- prefer readability over cleverness
- prefer straightforward solutions over excessive abstraction

For every phase, do not stop at implementation only. Also generate the relevant unit tests and feature tests for that phase, explain what they cover, and suggest whether they belong in the same commit or a separate test commit.

The end result should be an internship-worthy submission that is technically sound, easy to understand, and backed by a repo history that clearly tells the story of development.