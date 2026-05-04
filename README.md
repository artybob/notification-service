# Notification Service

Сервис уведомлений с гарантированной доставкой через email и telegram.

## Архитектура

### Паттерны
- **Strategy + Factory** для каналов доставки
- **Repository** для работы с БД
- **Queue Worker** для асинхронной отправки
- **Service Layer** для бизнес-логики

### Гарантия доставки
- Каждое уведомление попадает в очередь
- При ошибке - 3 попытки с задержкой 5, 15, 30 секунд
- Все попытки логируются
- После 3 неудач - статус `failed`

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
