# AI Cash Flow Forecast API

## Overview
This API uses an AI server (Ollama) running at `localhost:11434` to generate cash flow predictions based on historical data.

## Endpoints

### 1. Generate Forecast
**POST** `/api/ai/forecast/generate`

Generates AI-powered cash flow forecasts for a company.

#### Request Body
```json
{
  "company_id": 1,
  "months_to_predict": 6
}
```

#### Parameters
- `company_id` (required, integer): ID of the company to forecast for
- `months_to_predict` (optional, integer, 1-12): Number of months to predict (default: 6)

#### Response
```json
{
  "success": true,
  "message": "Forecast generated successfully",
  "data": [
    {
      "id": 1,
      "company_id": 1,
      "forecast_start": "2026-02-01",
      "forecast_end": "2026-02-01",
      "predicted_income": "150000.00",
      "predicted_expense": "120000.00",
      "predicted_balance": "30000.00",
      "risk_level": "low",
      "created_at": "2026-01-22T05:45:00.000000Z",
      "updated_at": "2026-01-22T05:45:00.000000Z",
      "company": {
        "id": 1,
        "name": "Sample Company",
        "industry": "Technology",
        "currency": "UZS"
      }
    }
  ]
}
```

### 2. Get Forecasts
**GET** `/api/ai/forecast/{company_id}`

Retrieves all forecasts for a specific company.

#### Parameters
- `company_id` (required, integer): ID of the company

#### Response
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "company_id": 1,
      "forecast_start": "2026-02-01",
      "forecast_end": "2026-02-01",
      "predicted_income": "150000.00",
      "predicted_expense": "120000.00",
      "predicted_balance": "30000.00",
      "risk_level": "low",
      "created_at": "2026-01-22T05:45:00.000000Z",
      "updated_at": "2026-01-22T05:45:00.000000Z",
      "company": {
        "id": 1,
        "name": "Sample Company",
        "industry": "Technology",
        "currency": "UZS"
      }
    }
  ]
}
```

### 3. Delete Forecasts
**DELETE** `/api/ai/forecast/{company_id}`

Deletes all forecasts for a specific company.

#### Parameters
- `company_id` (required, integer): ID of the company

#### Response
```json
{
  "success": true,
  "message": "Deleted 6 forecast records"
}
```

## AI Server Configuration

The API expects an Ollama server running at `http://localhost:11434` with the following settings:

- **Model**: `llama3.2` (configurable)
- **Temperature**: 0.3 (for consistent predictions)
- **Max Tokens**: 2000
- **Timeout**: 60 seconds

## Data Flow

1. **Input**: Historical cash flow data from `cashflow_summary` table
2. **Processing**: AI analyzes patterns and generates predictions
3. **Output**: Predictions saved to `forecasts` table

## AI Prompt Structure

The AI receives a structured prompt containing:
- Company information (name, industry, currency, averages)
- Historical cash flow data (JSON format)
- Clear instructions for prediction
- Expected output format
- Risk level criteria

## Error Handling

Common error responses:

```json
{
  "success": false,
  "message": "No historical data found for this company"
}
```

```json
{
  "success": false,
  "message": "Failed to connect to AI server: Connection refused"
}
```

```json
{
  "success": false,
  "message": "Failed to parse AI response: Invalid JSON"
}
```

## Usage Examples

### Using curl
```bash
# Generate forecast
curl -X POST http://localhost:8021/api/ai/forecast/generate \
  -H "Content-Type: application/json" \
  -d '{"company_id": 1, "months_to_predict": 6}'

# Get forecasts
curl http://localhost:8021/api/ai/forecast/1

# Delete forecasts
curl -X DELETE http://localhost:8021/api/ai/forecast/1
```

### Using JavaScript
```javascript
// Generate forecast
const response = await fetch('/api/ai/forecast/generate', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    company_id: 1,
    months_to_predict: 6
  })
});

const result = await response.json();
console.log(result);
```

## Testing

Run the test script to verify functionality:

```bash
php test_ai_forecast.php
```

## Configuration

AI settings can be modified in:
- Controller: `app/Http/Controllers/AI/AIForecastController.php`
- Template: `config/ai_forecast_template.json`

## Requirements

- Ollama server running at localhost:11434
- At least 3 months of historical data per company
- Valid company_id in the database
