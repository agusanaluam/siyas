export interface SnapTransactionResult {
  transaction_status: string
  transaction_id: string
  order_id: string
  gross_amount: string
  payment_type?: string
  fraud_status?: string
  status_code?: string
  status_message?: string
}

export interface SnapCallbacks {
  onSuccess?: (result: SnapTransactionResult) => void
  onPending?: (result: SnapTransactionResult) => void
  onError?: (result: SnapTransactionResult) => void
  onClose?: () => void
}

export interface Snap {
  pay: (token: string, callbacks?: SnapCallbacks) => void
  embed: (token: string, options?: { embedId?: string }) => void
}

declare global {
  interface Window {
    snap: Snap
  }
}

export {}
