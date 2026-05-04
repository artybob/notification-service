#!/bin/bash

echo "=== Notification Service Demo ==="
echo ""

echo "1. Создаём уведомление:"
curl -s -X POST http://localhost:8080/api/notifications \
  -H "Content-Type: application/json" \
  -d '{"user_id":1,"channel":"email","message":"Hello World"}' | json_pp 2>/dev/null || cat

echo ""
echo "2. История пользователя:"
curl -s "http://localhost:8080/api/notifications/user/1/history" | json_pp 2>/dev/null || cat

echo ""
echo "3. Генерируем отчёт:"
curl -s -X POST http://localhost:8080/api/reports/generate \
  -H "Content-Type: application/json" \
  -d '{"user_id":1,"start_date":"2024-01-01","end_date":"2025-12-31"}' | json_pp 2>/dev/null || cat

echo ""
echo "4. Статус отчёта:"
sleep 2
curl -s http://localhost:8080/api/reports/1/status | json_pp 2>/dev/null || cat
