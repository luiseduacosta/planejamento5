# Faculty Profile Management

<cite>
**Referenced Files in This Document**
- [Docente.php](file://src/Model/Entity/Docente.php)
- [ProfessoresTable.php](file://src/Model/Table/ProfessoresTable.php)
- [ProfessoresController.php](file://src/Controller/ProfessoresController.php)
- [index.php](file://templates/Professores/index.php)
- [add.php](file://templates/Professores/add.php)
- [edit.php](file://templates/Professores/edit.php)
- [view.php](file://templates/Professores/view.php)
- [CreateDocenteDisponibilidades.php](file://config/Migrations/20260613100000_CreateDocenteDisponibilidades.php)
- [DocenteDisponibilidade.php](file://src/Model/Entity/DocenteDisponibilidade.php)
- [DocenteDisponibilidadesTable.php](file://src/Model/Table/DocenteDisponibilidadesTable.php)
</cite>

## Table of Contents
1. [Introduction](#introduction)
2. [Project Structure](#project-structure)
3. [Core Components](#core-components)
4. [Architecture Overview](#architecture-overview)
5. [Detailed Component Analysis](#detailed-component-analysis)
6. [Dependency Analysis](#dependency-analysis)
7. [Performance Considerations](#performance-considerations)
8. [Troubleshooting Guide](#troubleshooting-guide)
9. [Conclusion](#conclusion)

## Introduction
This document explains the complete CRUD operations for managing faculty profiles (Docente). It covers:
- The Docente entity structure and fields
- Status management with canonical values and aliases
- Filtering by status, department, and planning availability
- Adding new faculty members, editing existing profiles, and deleting records
- Index page features including sorting and bulk actions via links
- Availability per planning configuration and how it integrates with filtering

The system is implemented using CakePHP conventions with an MVC architecture.

## Project Structure
Faculty profile management spans controllers, models, entities, views, and a related availability table:
- Controller: ProfessoresController handles index, view, add, edit, delete
- Model: ProfessoresTable defines validation, relationships, and status normalization
- Entity: Docente defines accessible fields and metadata
- Views: templates/Professores/* provide UI for listing, viewing, adding, and editing
- Related model: DocenteDisponibilidades manages availability per planning configuration

```mermaid
graph TB
subgraph "Controllers"
C["ProfessoresController"]
end
subgraph "Models"
T["ProfessoresTable"]
E["Docente (Entity)"]
TD["DocenteDisponibilidadesTable"]
ED["DocenteDisponibilidade (Entity)"]
end
subgraph "Views"
VIdx["templates/Professores/index.php"]
VAdd["templates/Professores/add.php"]
VEdit["templates/Professores/edit.php"]
VView["templates/Professores/view.php"]
end
subgraph "DB Migrations"
MAvail["CreateDocenteDisponibilidades migration"]
end
C --> T
T --> E
C --> TD
TD --> ED
C --> VIdx
C --> VAdd
C --> VEdit
C --> VView
TD -.-> MAvail
```

**Diagram sources**
- [ProfessoresController.php:1-247](file://src/Controller/ProfessoresController.php#L1-L247)
- [ProfessoresTable.php:1-126](file://src/Model/Table/ProfessoresTable.php#L1-L126)
- [Docente.php:1-57](file://src/Model/Entity/Docente.php#L1-L57)
- [DocenteDisponibilidadesTable.php:1-77](file://src/Model/Table/DocenteDisponibilidadesTable.php#L1-L77)
- [DocenteDisponibilidade.php:1-22](file://src/Model/Entity/DocenteDisponibilidade.php#L1-L22)
- [CreateDocenteDisponibilidades.php:1-48](file://config/Migrations/20260613100000_CreateDocenteDisponibilidades.php#L1-L48)
- [index.php:1-166](file://templates/Professores/index.php#L1-L166)
- [add.php:1-44](file://templates/Professores/add.php#L1-L44)
- [edit.php:1-44](file://templates/Professores/edit.php#L1-L44)
- [view.php:1-153](file://templates/Professores/view.php#L1-L153)

**Section sources**
- [ProfessoresController.php:1-247](file://src/Controller/ProfessoresController.php#L1-L247)
- [ProfessoresTable.php:1-126](file://src/Model/Table/ProfessoresTable.php#L1-L126)
- [Docente.php:1-57](file://src/Model/Entity/Docente.php#L1-L57)
- [index.php:1-166](file://templates/Professores/index.php#L1-L166)
- [add.php:1-44](file://templates/Professores/add.php#L1-L44)
- [edit.php:1-44](file://templates/Professores/edit.php#L1-L44)
- [view.php:1-153](file://templates/Professores/view.php#L1-L153)
- [CreateDocenteDisponibilidades.php:1-48](file://config/Migrations/20260613100000_CreateDocenteDisponibilidades.php#L1-L48)
- [DocenteDisponibilidadesTable.php:1-77](file://src/Model/Table/DocenteDisponibilidadesTable.php#L1-L77)
- [DocenteDisponibilidade.php:1-22](file://src/Model/Entity/DocenteDisponibilidade.php#L1-L22)

## Core Components
- Docente entity: Defines all fields that can be mass-assigned and persisted. Includes identification, contact info, employment details, and status.
- ProfessoresTable: Provides validation rules, relationships to Planejamento and DocenteDisponibilidade, and status normalization on input.
- ProfessoresController: Implements full CRUD and advanced filtering on the index action.
- Templates: Provide forms and list views with sorting, pagination, and filters.

Key responsibilities:
- Data integrity through validation rules
- Canonical status handling across inputs and queries
- Filtering by status, department, and availability for a specific planning configuration
- Sorting and pagination on the index page

**Section sources**
- [Docente.php:1-57](file://src/Model/Entity/Docente.php#L1-L57)
- [ProfessoresTable.php:1-126](file://src/Model/Table/ProfessoresTable.php#L1-L126)
- [ProfessoresController.php:1-247](file://src/Controller/ProfessoresController.php#L1-L247)
- [index.php:1-166](file://templates/Professores/index.php#L1-L166)
- [add.php:1-44](file://templates/Professores/add.php#L1-L44)
- [edit.php:1-44](file://templates/Professores/edit.php#L1-L44)
- [view.php:1-153](file://templates/Professores/view.php#L1-L153)

## Architecture Overview
The application follows MVC:
- Requests hit ProfessoresController methods
- Controllers use ProfessoresTable to query/save data
- Entities represent rows and define accessibility
- Views render lists, forms, and detail pages
- Availability is managed via DocenteDisponibilidades linked to Docente and Configuraplanejamento

```mermaid
sequenceDiagram
participant U as "User"
participant C as "ProfessoresController"
participant T as "ProfessoresTable"
participant D as "DocenteDisponibilidadesTable"
participant V as "Templates/Professores/*"
U->>C : GET /professores (index)
C->>T : find() + filters (status, departamento, configuracao)
C->>D : load available configurations and current active one
C-->>V : render index with filtered/sorted results
U->>C : POST /professores/add
C->>T : patchEntity() + save()
C-->>U : redirect to view
U->>C : PATCH /professores/edit/{id}
C->>T : patchEntity() + save()
C-->>U : redirect to view
U->>C : DELETE /professores/delete/{id}
C->>T : delete()
C-->>U : redirect to index
```

**Diagram sources**
- [ProfessoresController.php:34-171](file://src/Controller/ProfessoresController.php#L34-L171)
- [ProfessoresTable.php:26-42](file://src/Model/Table/ProfessoresTable.php#L26-L42)
- [DocenteDisponibilidadesTable.php:13-30](file://src/Model/Table/DocenteDisponibilidadesTable.php#L13-L30)
- [index.php:1-166](file://templates/Professores/index.php#L1-L166)
- [add.php:1-44](file://templates/Professores/add.php#L1-L44)
- [edit.php:1-44](file://templates/Professores/edit.php#L1-L44)
- [view.php:1-153](file://templates/Professores/view.php#L1-L153)

## Detailed Component Analysis

### Professor Entity Structure
Fields supported by the entity include:
- Identification: id, nome, cpf, siape, cress, regiao
- Contact: telefone, celular, email
- Employment: dataingresso, tipocargo, departamento, dataegresso, motivoegresso
- Status and notes: status, observacoes
- Audit: created, modified

All these fields are marked accessible for mass assignment.

Status field accepts canonical values and aliases:
- Canonical: ativo, aposentado, inativo
- Aliases normalized to canonical: active/activo -> ativo; retired -> aposentado; inactive/inactivo -> inativo

Normalization occurs during marshalling so stored values are always canonical.

**Section sources**
- [Docente.php:1-57](file://src/Model/Entity/Docente.php#L1-L57)
- [ProfessoresTable.php:15-21](file://src/Model/Table/ProfessoresTable.php#L15-L21)
- [ProfessoresTable.php:114-124](file://src/Model/Table/ProfessoresTable.php#L114-L124)

### Validation Rules
- nome: required on create, scalar, max length
- Email: optional, validated as email when present
- Dates: dataingresso and dataegresso are optional dates
- Other text fields: optional scalars
- status: optional scalar; normalized before persisting

These rules ensure consistent and safe data entry.

**Section sources**
- [ProfessoresTable.php:47-112](file://src/Model/Table/ProfessoresTable.php#L47-L112)

### Relationships
- One-to-many to Planejamentos via docente_id
- One-to-many to DocenteDisponibilidades via docente_id

Availability records link each docente to a planning configuration and indicate whether they are available.

**Section sources**
- [ProfessoresTable.php:35-42](file://src/Model/Table/ProfessoresTable.php#L35-L42)
- [DocenteDisponibilidadesTable.php:22-30](file://src/Model/Table/DocenteDisponibilidadesTable.php#L22-L30)
- [CreateDocenteDisponibilidades.php:10-45](file://config/Migrations/20260613100000_CreateDocenteDisponibilidades.php#L10-L45)

### Status Management and Aliases
Canonical statuses:
- ativo (active)
- aposentado (retired)
- inativo (inactive)

Aliases accepted from user input or external systems:
- ativo: ["ativo", "active", "activo"]
- aposentado: ["aposentado", "retired"]
- inativo: ["inativo", "inactive", "inactivo"]

Normalization happens in beforeMarshal, ensuring database consistency. Display labels map canonical values to human-friendly strings.

```mermaid
flowchart TD
Start(["Input status"]) --> CheckType{"Is string?"}
CheckType --> |No| End(["Return unchanged"])
CheckType --> |Yes| Lookup["Lookup alias map"]
Lookup --> Found{"Alias found?"}
Found --> |Yes| Normalize["Replace with canonical"]
Found --> |No| Keep["Keep original value"]
Normalize --> End
Keep --> End
```

**Diagram sources**
- [ProfessoresTable.php:114-124](file://src/Model/Table/ProfessoresTable.php#L114-L124)
- [ProfessoresTable.php:15-21](file://src/Model/Table/ProfessoresTable.php#L15-L21)

**Section sources**
- [ProfessoresTable.php:15-21](file://src/Model/Table/ProfessoresTable.php#L15-L21)
- [ProfessoresTable.php:114-124](file://src/Model/Table/ProfessoresTable.php#L114-L124)
- [ProfessoresController.php:16-26](file://src/Controller/ProfessoresController.php#L16-L26)

### Filtering System
Index supports three filters:
- Status: uses canonical values and aliases; displays friendly labels
- Department: exact match against departamento
- Planning availability: shows professores who have a disponivel=true record for the selected configuraplanejamento

The controller builds a query with optional where clauses and matching joins for availability.

```mermaid
flowchart TD
A["Request index with query params"] --> B["Load unique departamentos and statuses"]
B --> C["Load configuracoes with availability"]
C --> D{"Filters present?"}
D --> |status| E["Apply IN clause with aliases"]
D --> |departamento| F["Filter by departamento"]
D --> |configuraplanejamento_id| G["matching('DocenteDisponibilidades') where disponivel=true"]
E --> H["Paginate and set context"]
F --> H
G --> H
H --> I["Render index with sortable columns and badges"]
```

**Diagram sources**
- [ProfessoresController.php:34-171](file://src/Controller/ProfessoresController.php#L34-L171)
- [index.php:40-94](file://templates/Professores/index.php#L40-L94)

**Section sources**
- [ProfessoresController.php:34-171](file://src/Controller/ProfessoresController.php#L34-L171)
- [index.php:40-94](file://templates/Professores/index.php#L40-L94)

### Index Page Features
- Sortable columns: id, nome, siape, departamento, tipocargo, status, email
- Availability column shows Yes/No for the active or selected planning configuration, with optional reason tooltip
- Active filter badges display current filters
- Pagination controls at the bottom

Note: While the prompt mentions periodo_diurno and periodo_noturno, the Docente entity does not include those fields. Availability is modeled via DocenteDisponibilidades instead.

**Section sources**
- [index.php:96-165](file://templates/Professores/index.php#L96-L165)
- [ProfessoresController.php:107-123](file://src/Controller/ProfessoresController.php#L107-L123)

### Add New Faculty Member
- Default status is set to ativo
- Form includes all relevant fields with appropriate types and options
- On successful save, redirects to the view page

Example flow:
- User opens add form
- Submits data
- Controller patches entity, validates, saves
- Flash message displayed and redirect to view

**Section sources**
- [ProfessoresController.php:183-200](file://src/Controller/ProfessoresController.php#L183-L200)
- [add.php:1-44](file://templates/Professores/add.php#L1-L44)

### Edit Existing Profile
- Loads existing record
- Normalizes status for display
- Accepts updates via patch/put/post
- Redirects to view after success

**Section sources**
- [ProfessoresController.php:213-230](file://src/Controller/ProfessoresController.php#L213-L230)
- [edit.php:1-44](file://templates/Professores/edit.php#L1-L44)

### Delete Record
- Requires POST or DELETE method
- Authorizes deletion
- Deletes record and redirects to index with flash messages

**Section sources**
- [ProfessoresController.php:232-245](file://src/Controller/ProfessoresController.php#L232-L245)

### Managing Faculty Status Transitions
To change a professor’s status:
- Open the edit form
- Select the desired status (ativo, aposentado, inativo)
- Save changes
- The system normalizes any alias to canonical before saving

Bulk operations:
- The index provides individual delete links per row
- There is no explicit multi-select bulk action implementation in the provided files

**Section sources**
- [ProfessoresController.php:213-230](file://src/Controller/ProfessoresController.php#L213-L230)
- [index.php:140-147](file://templates/Professores/index.php#L140-L147)

### Availability Per Planning Configuration
- Each docente can be marked available or unavailable for a specific planning configuration
- The index can filter professores who are available for a given configuration
- The view page lists availability records with semester, availability flag, reason, and actions

```mermaid
erDiagram
DOCENTE {
int id PK
string nome
string cpf
string siape
string departamento
string tipocargo
string status
}
DOCENTE_DISPONIBILIDADE {
int id PK
int docente_id FK
int configuraplanejamento_id FK
boolean disponivel
string motivo
}
CONFIGURAPLANJAMENTO {
int id PK
string semestre
string versao
boolean ativo
}
DOCENTE ||--o{ DOCENTE_DISPONIBILIDADE : "has many"
CONFIGURAPLANJAMENTO ||--o{ DOCENTE_DISPONIBILIDADE : "has many"
```

**Diagram sources**
- [CreateDocenteDisponibilidades.php:10-45](file://config/Migrations/20260613100000_CreateDocenteDisponibilidades.php#L10-L45)
- [DocenteDisponibilidadesTable.php:22-30](file://src/Model/Table/DocenteDisponibilidadesTable.php#L22-L30)
- [DocenteDisponibilidade.php:10-20](file://src/Model/Entity/DocenteDisponibilidade.php#L10-L20)

**Section sources**
- [DocenteDisponibilidadesTable.php:1-77](file://src/Model/Table/DocenteDisponibilidadesTable.php#L1-L77)
- [DocenteDisponibilidade.php:1-22](file://src/Model/Entity/DocenteDisponibilidade.php#L1-L22)
- [CreateDocenteDisponibilidades.php:1-48](file://config/Migrations/20260613100000_CreateDocenteDisponibilidades.php#L1-L48)
- [view.php:100-144](file://templates/Professores/view.php#L100-L144)

## Dependency Analysis
- ProfessoresController depends on ProfessoresTable and DocenteDisponibilidadesTable
- ProfessoresTable has relationships to Planejamentos and DocenteDisponibilidades
- Views depend on controller-provided variables for rendering filters, lists, and actions

```mermaid
classDiagram
class ProfessoresController {
+index()
+view(id)
+add()
+edit(id)
+delete(id)
}
class ProfessoresTable {
+initialize(config)
+validationDefault(validator)
+beforeMarshal(event, data, options)
}
class Docente {
+id
+nome
+cpf
+siape
+departamento
+tipocargo
+status
+email
+telefone
+celular
+dataingresso
+dataegresso
+motivoegresso
+observacoes
+created
+modified
}
class DocenteDisponibilidadesTable {
+initialize(config)
+validationDefault(validator)
+buildRules(rules)
}
class DocenteDisponibilidade {
+docente_id
+configuraplanejamento_id
+disponivel
+motivo
+observacoes
+created
+modified
}
ProfessoresController --> ProfessoresTable : "uses"
ProfessoresController --> DocenteDisponibilidadesTable : "uses"
ProfessoresTable --> Docente : "manages"
DocenteDisponibilidadesTable --> DocenteDisponibilidade : "manages"
```

**Diagram sources**
- [ProfessoresController.php:1-247](file://src/Controller/ProfessoresController.php#L1-L247)
- [ProfessoresTable.php:1-126](file://src/Model/Table/ProfessoresTable.php#L1-L126)
- [Docente.php:1-57](file://src/Model/Entity/Docente.php#L1-L57)
- [DocenteDisponibilidadesTable.php:1-77](file://src/Model/Table/DocenteDisponibilidadesTable.php#L1-L77)
- [DocenteDisponibilidade.php:1-22](file://src/Model/Entity/DocenteDisponibilidade.php#L1-L22)

**Section sources**
- [ProfessoresController.php:1-247](file://src/Controller/ProfessoresController.php#L1-L247)
- [ProfessoresTable.php:1-126](file://src/Model/Table/ProfessoresTable.php#L1-L126)
- [Docente.php:1-57](file://src/Model/Entity/Docente.php#L1-L57)
- [DocenteDisponibilidadesTable.php:1-77](file://src/Model/Table/DocenteDisponibilidadesTable.php#L1-L77)
- [DocenteDisponibilidade.php:1-22](file://src/Model/Entity/DocenteDisponibilidade.php#L1-L22)

## Performance Considerations
- Use distinct queries to populate dropdowns for departments, statuses, and configurations
- Apply filters early in the query builder to reduce result sets
- Leverage pagination to avoid loading large datasets into memory
- Avoid unnecessary contains in index; only fetch what is needed for display
- Ensure indexes exist on foreign keys and frequently filtered columns (e.g., status, departamento, configuraplanejamento_id)

[No sources needed since this section provides general guidance]

## Troubleshooting Guide
Common issues and resolutions:
- Status not saved as expected: Verify that input values are recognized aliases; the system normalizes them to canonical values during marshalling.
- Filter not returning results: Confirm that the selected planning configuration has availability records with disponivel=true for the intended professores.
- Validation errors on submit: Check required fields like nome and ensure email format if provided.
- Missing availability information: Ensure availability records exist for the selected configuration; otherwise, the index will show “Not informed.”

**Section sources**
- [ProfessoresTable.php:114-124](file://src/Model/Table/ProfessoresTable.php#L114-L124)
- [ProfessoresController.php:88-105](file://src/Controller/ProfessoresController.php#L88-L105)
- [index.php:125-137](file://templates/Professores/index.php#L125-L137)

## Conclusion
The faculty profile management module provides robust CRUD capabilities for professores, with strong validation, canonical status handling, and flexible filtering by status, department, and planning availability. The index page supports sorting and pagination, while the view page exposes availability per planning configuration. The design cleanly separates concerns across controller, model, entity, and template layers, making maintenance and extension straightforward.

[No sources needed since this section summarizes without analyzing specific files]