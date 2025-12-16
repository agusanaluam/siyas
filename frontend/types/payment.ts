export interface PaymentItem {
  id: string
  name: string
  price: number
  quantity: number
}

export interface CheckoutRequest {
  order_id: string
  amount: number
  email: string
  name: string
  phone: string
  items: PaymentItem[]
  description?: string
  callback_url?: string
}

export interface CheckoutResponse {
  token: string
  redirect_url?: string
}

export interface TransactionResult {
  transaction_status: string
  transaction_id: string
  order_id: string
  gross_amount: number
  payment_type?: string
  fraud_status?: string
  status_code?: string
}
