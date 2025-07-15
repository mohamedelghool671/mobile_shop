# 🛍️ Mobile Shop Backend API

**نظام باك إند متكامل لتطبيق موبايل خاص بمتجر ملابس (E-commerce App)**

📌 مشروع مبني باستخدام Laravel معمارية نظيفة Clean Architecture، وبيقدّم كل الإمكانيات اللي ممكن يحتاجها أي تطبيق تجارة إلكترونية احترافي.

---

## 🚀 المميزات الأساسية

### ⚙️ Clean Architecture

* ✅ فصل كامل بين منطق الأعمال (Business Logic) وطبقة التوجيه (Controller)
* ✅ استخدام Repository Pattern + Service Layer
* ✅ كل كلاس مسؤول عن شيء واحد فقط (Single Responsibility Principle)

---

### 🔐 نظام مصادقة متكامل (Authentication System)

* 🔸 تسجيل مستخدم جديد وإرسال رابط تفعيل إلى البريد الإلكتروني
* 🔸 إمكانية استرجاع كلمة المرور باستخدام رابط على الإيميل
* 🔸 كل عمليات الإرسال تعتمد على Queues لتقليل الضغط وتحسين الأداء
* 🔄 Job مجدولة تلقائيًا كل أسبوع لمسح الحسابات غير المفعّلة

---

### 🔔 إشعارات لحظية باستخدام Firebase

* 📲 إرسال إشعارات باستخدام Firebase Cloud Messaging (FCM)
* 🧠 Event & Listener structure لكل الأحداث
* 🛎️ إشعار عند حدوث عمليات مهمة مثل:

  * تغيير حالة طلب
  * إضافة خصم جديد
  * إشعار ترويجي

---

### 💳 التكامل مع Stripe للدفع الإلكتروني

* 💰 دعم دفع آمن من خلال Stripe Checkout
* 🧾 Webhook Listener مخصص لتحديث حالة الطلب تلقائيًا بعد الدفع
* 📦 كل عمليات الدفع مُدارة داخل Service منفصلة ومهيكلة بوضوح

---

### ⚡ تحسين الأداء باستخدام التخزين المؤقت (Caching)

* 🧠 استخدام Redis لتخزين:

  * التصنيفات
  * المنتجات الشائعة
  * الفلاتر
* 📉 تقليل الضغط على قاعدة البيانات وتحسين سرعة الاستجابة

---

## 🛠️ مميزات إضافية

* 🔒 حماية الـ API باستخدام Rate Limiting
* 📥 Validation باستخدام Laravel Form Request
* ⚠️ Exception Handler موحّد لإرجاع استجابات مفهومة للمستخدمين
* 📡 نظام استجابة موحّد لكل الـ APIs
* 📬 جميع العمليات الثقيلة (إيميلات – دفع – إشعارات) تتم داخل Queues

---

## 📁 هيكل المشروع

```
app/
├── Http/Controllers         -> API Controllers
├── Services                 -> منطق الأعمال
├── Repositories             -> الوصول للبيانات
├── Events                   -> الأحداث المهمة داخل النظام
├── Listeners                -> التعامل مع الأحداث
├── Jobs                     -> إرسال البريد - حذف المستخدمين
├── Notifications            -> إشعارات مخصصة
├── Webhook/StripeWebhook    -> التعامل مع Webhook من Stripe
```

---

## 🧪 اختبار وتشغيل المشروع

### المتطلبات:

* PHP >= 8.x
* MySQL / PostgreSQL
* Redis
* Composer
* Node.js + NPM

### خطوات التشغيل:

```bash
git clone https://github.com/mohamedelghool671/mobile_shop.git
cd mobile_shop

composer install
npm install && npm run dev

cp .env.example .env
php artisan key:generate

php artisan migrate --seed

php artisan queue:work &   # لتشغيل الطوابير
php artisan serve          # لتشغيل التطبيق
```

### إعداد الـ Scheduler:

* أضف هذا السطر إلى `crontab -e`

```bash
* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
```

---

## 🔑 إعداد ملف البيئة (.env)

```env
APP_NAME=MobileShopAPI
APP_ENV=local
APP_KEY=base64:...
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mobile_shop
DB_USERNAME=root
DB_PASSWORD=

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=admin@mobileshop.com
MAIL_FROM_NAME="MobileShop"

STRIPE_KEY=...
STRIPE_SECRET=...
FIREBASE_SERVER_KEY=...
```

---

## 📦 API جاهز للتكامل

* ✅ واجهات برمجية جاهزة لأي تطبيق موبايل (iOS/Android)
* ✅ استجابات JSON موحدة وسهلة الفهم
* ✅ توثيق كامل ممكن إضافته باستخدام Postman أو Swagger

---

## 📄 الرخصة

المشروع متاح بموجب رخصة MIT License — انطلق وعدّل بحرية ✌️

---

## 🤝 المساهمة والتواصل

لو عندك أي أفكار لتطوير المشروع أو بتحب تساهم فيه:

* افتح Issue جديدة
* أو ابعت Pull Request
* أو كلمني مباشرة على LinkedIn ✨

> ⭐ لا تنسى تعمل ⭐ Star للمستودع لو عجبك الشغل!
