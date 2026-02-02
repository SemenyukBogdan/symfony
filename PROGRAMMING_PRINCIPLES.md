# Принципи програмування в проєкті

Опис дотримання принципів програмування з посиланнями на код репозиторію (гілка lab8).

---

## 1. Єдина відповідальність (Single Responsibility Principle)

Кожен клас/модуль має одну чітку зону відповідальності.

- **Entity** — тільки модель даних та зв’язки з БД (атрибути, геттери/сеттери). Приклад: [src/Entity/Book.php](src/Entity/Book.php) — клас лише описує сутність «книга» (рядки 34–181).

- **Controller** — тільки обробка HTTP-запиту та повернення відповіді. [src/Controller/AuthController.php](src/Controller/AuthController.php) містить лише маршрут і логіку логіну (рядки 9–16).

- **Service** — бізнес-логіка в одній предметній області. Наприклад, [src/Services/BorrowingsService/BorrowingsService.php](src/Services/BorrowingsService/BorrowingsService.php) відповідає лише за створення та оновлення позик (рядки 9–94).

- **RequestCheckerService** — лише перевірка та валідація вхідних даних: [src/Services/RequestCheckerService.php](src/Services/RequestCheckerService.php) (рядки 9–74).

---

## 2. Розділення відповідальностей (Separation of Concerns)

Логіка рознесена по шарах: Entity, Repository, Service, Controller, Form.

- Моделі даних: [src/Entity/](src/Entity/)
- Доступ до даних: [src/Repository/](src/Repository/)
- Бізнес-логіка: [src/Services/](src/Services/)
- HTTP та маршрути: [src/Controller/](src/Controller/)
- Форми: [src/Form/](src/Form/)

Контролер не містить прямого доступу до БД; він використовує сервіси. Сервіси використовують `EntityManager` та інші сервіси (наприклад, `RequestCheckerService` у [BorrowingsService](src/Services/BorrowingsService/BorrowingsService.php#L23-L27)).

---

## 3. Іменування (Meaningful Names)

Назви класів і методів відображають їх призначення.

- Класи сутностей: `Book`, `Author`, `Borrowing`, `Reader` — [src/Entity/](src/Entity/).
- Сервіси за сутностями: `AuthorsService`, `BorrowingsService` — [src/Services/](src/Services/).
- Методи: `createBorrowing`, `updateBorrowing`, `validateRequestDataByConstraints`, `check` — зрозуміло, що робить кожен метод.
- Репозиторії: `BooksRepository`, `AuthorsRepository` — [src/Repository/](src/Repository/).

---

## 4. Не повторюйся (DRY)

Спільна логіка винесена в окремі сервіси.

- Валідація через **RequestCheckerService** використовується в різних сервісах замість дублювання коду валідації:
  - [BorrowingsService](src/Services/BorrowingsService/BorrowingsService.php#L42) — `validateRequestDataByConstraints($borrowings)`
  - [AuthorsService](src/Services/AuthorsService/AuthorsService.php#L37) — той самий метод для авторів

- Патерн оновлення сутності з масиву даних (цикл по полях і виклик сетерів) винесений у методи на кшталт `updateBorrowing` / `updateAuthor`, щоб не дублювати таку логіку в кожному місці.

---

## 5. Інкапсуляція та залежності

Сервіси отримують залежності через конструктор (dependency injection), а не створюють їх всередині.

- [BorrowingsService](src/Services/BorrowingsService/BorrowingsService.php#L23-L27): `EntityManagerInterface` та `RequestCheckerService` передаються в конструктор.
- [RequestCheckerService](src/Services/RequestCheckerService.php#L16-L19): отримує `ValidatorInterface` через конструктор.

Це спрощує тестування та зміну реалізацій без зміни коду сервісів.
