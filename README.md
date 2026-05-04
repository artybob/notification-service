# Notification Service

Сервис уведомлений с гарантированной доставкой через email и telegram.

## Архитектура

### Архитектурные решения
- **Паттерн Стратегия** для каналов отправки (легко добавить новый канал)
- **Очереди (Queue)** для гарантии доставки и асинхронной обработки
- **Job с retry механизмом** (3 попытки с задержкой 5, 15, 30 секунд)
- **Service layer** для бизнес-логики (контроллеры тонкие)
- **Docker** для полной изоляции окружения

### Гарантия доставки
- Каждое уведомление попадает в очередь
- При ошибке - 3 попытки с задержкой 5, 15, 30 секунд
- Все попытки логируются
- После 3 неудач - статус `failed`

### Что улучшить в продакшене:

- Добавить мониторинг (Sentry / Laravel Horizon)
- Rate limiting для предотвращения спама
- Webhook'и для уведомления внешних систем о статусе
- Шаблоны уведомлений с переменными
- Dead Letter Queue для permanently failed уведомлений
- Кеширование статистики для отчётов

Шаблоны уведомлений

## Запуск

```bash
# Клонирование
git clone <repo>
cd notification-service

# Настройка
cp .env.example .env

# Docker
make setup

# Запуск воркера (в отдельном терминале)
make queue
```

## Быстрый тест всех API
```bash
./demo.sh
```
##  Тестирование
```bash
bash
make test        # Запуск тестов
make phpstan     # Статический анализ (уровень 5)
make pint        # Code style fixer
```

## Команды Make
```bash
make up          # Запуск контейнеров
make down        # Остановка
make shell       # Вход в контейнер
make logs        # Просмотр логов
make clean       # Полная очистка
```

### API Endpoints:

POST /api/notifications - создать уведомление

GET /api/notifications/{id} - статус

GET /api/notifications/user/{userId}/history - история

POST /api/reports/generate - генерация отчёта

GET /api/reports/{id}/status - статус отчёта

GET /api/reports/{id}/download - скачать отчёт

