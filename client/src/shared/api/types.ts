export interface ApiResponse<T> {
  data: T
  message?: string
}

export interface ValidationError {
  message: string
  errors: Record<string, string[]>
}

export interface ApiError {
  message: string
  status: number
}
