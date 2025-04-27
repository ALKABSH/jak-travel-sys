# العلاقات بين جداول قاعدة البيانات (ERD) مع توزيع المهام والأدوار

## الجداول الرئيسية والأدوار والمهام:

### 🏢 agencies (الوكالات)
- جدول رئيسي (عادة سجل واحد).
- [id] مفتاح أساسي.
- **مهام الوكيل الرئيسي:**
    - تحكم شامل في الوكالة وخدماتها
    - إدارة السبوكلاء والعملاء التابعين
    - توليد التقارير وإعداد العروض
    - إدارة العملات والتحويلات

### 🧑‍💻 users (المستخدمون)
- [id] مفتاح أساسي.
- [agency_id] مفتاح خارجي ← agencies.id
- [role] يحدد الدور (admin, agency, subagent, client)
- [parent_id] (اختياري) يشير إلى المستخدم المسؤول (للسبوكلاء)
- **مهام الأدمن:**
    - تحكم كامل بالتطبيق وميزاته
    - إدارة إعدادات النظام العامة
    - إدارة وتعديل صلاحيات المستخدمين
- **مهام العميل:**
    - استعراض الخدمات المتاحة
    - تقديم طلبات خدمة جديدة
    - متابعة حالة الطلبات القائمة
    - استلام العروض والموافقة عليها
- **مهام السبوكيل:**
    - يمكن أن يضيف خدمات خاصة به (تعتبر ضمن خدمات الوكيل الرئيسي)
    - تقديم عروض أسعار للخدمات
    - متابعة الطلبات وتحديثها

### 🧑‍🔧 subagents (السبوكلاء)
- هم مستخدمون في users (role=subagent)
- [agency_id] ← agencies.id
- علاقة N:N مع الخدمات عبر جدول وسيط (service_subagent)
- **مهام السبوكيل:**
    - إضافة خدمات خاصة (تظهر ضمن خدمات الوكيل)
    - تقديم عروض أسعار
    - متابعة وتحديث الطلبات

### 👤 clients (العملاء)
- هم مستخدمون في users (role=client)
- [agency_id] ← agencies.id
- **مهام العميل:**
    - استعراض الخدمات
    - تقديم الطلبات
    - متابعة حالة الطلبات
    - استلام العروض والموافقة عليها

### 🧾 services (الخدمات)
- [id] مفتاح أساسي
- [agency_id] ← agencies.id
- علاقة N:N مع السبوكلاء عبر جدول وسيط (service_subagent)
- **ملاحظات:**
    - يمكن للوكيل والسبوكلاء إدارة الخدمات حسب الصلاحيات

### 📦 requests (الطلبات)
- [id] مفتاح أساسي
- [user_id] ← users.id (العميل)
- [agency_id] ← agencies.id
- [service_id] ← services.id
- **ملاحظات:**
    - ينشئها العميل وتديرها الوكالة وتوجهها للسبوكلاء

### 💰 quotes (العروض)
- [id] مفتاح أساسي
- [request_id] ← requests.id
- [subagent_id] ← users.id (السبوكيل)
- [currency_id] ← currencies.id
- [agency_id] ← agencies.id
- **ملاحظات:**
    - ينشئها السبوكلاء وتراجعها الوكالة وتعرض للعميل

### 💳 payments (المدفوعات)
- [id] مفتاح أساسي
- [user_id] ← users.id (العميل)
- [quote_id] ← quotes.id
- [agency_id] ← agencies.id
- **ملاحظات:**
    - جميع المدفوعات بين العميل والوكالة فقط

### 💱 currencies (العملات)
- [id] مفتاح أساسي
- تستخدم في quotes, services, agencies
- **مهام الوكيل:**
    - إدارة العملات والتحويلات

---

## الجداول الوسيطة (Pivot Tables)
- **service_subagent**
  - [service_id] ← services.id
  - [subagent_id] ← users.id
  - [custom_commission_rate] نسبة العمولة المخصصة
  - [is_active] حالة الربط

---

## ملخص العلاقات (Foreign Keys)
- users.agency_id → agencies.id
- users.parent_id → users.id (اختياري)
- requests.user_id → users.id
- requests.agency_id → agencies.id
- requests.service_id → services.id
- quotes.request_id → requests.id
- quotes.subagent_id → users.id
- quotes.currency_id → currencies.id
- quotes.agency_id → agencies.id
- services.agency_id → agencies.id
- payments.user_id → users.id
- payments.quote_id → quotes.id
- payments.agency_id → agencies.id
- service_subagent.service_id → services.id
- service_subagent.subagent_id → users.id

---

## ملاحظات:
- جميع العمليات المالية بين العميل والوكالة فقط.
- السبوكلاء لا يرون بيانات العميل مباشرة.
- جميع الطلبات والعروض تمر عبر الوكالة.
- العملات يحددها السبوكلاء في العروض، وتتحكم الوكالة في العملات المسموح بها.

---

هذا التوثيق يعكس بنية العلاقات الفعلية في النظام ويساعد في فهم تدفق البيانات والصلاحيات بدقة.
