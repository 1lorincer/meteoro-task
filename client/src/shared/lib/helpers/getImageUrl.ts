/**
 * Формирует полный URL для изображения с бэкенда
 * @param imagePath - путь к изображению (например, "products/image.webp")
 * @returns полный URL изображения или null если путь не указан
 */

export const getImageUrl = (imagePath: string | null | undefined): string | null => {
  if (!imagePath) {
    return null
  }

  if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
    return imagePath
  }

  const baseUrl = import.meta.env.VITE_API_URL?.replace('/api', '') || 'http://localhost:8000'

  const cleanPath = imagePath.startsWith('/') ? imagePath.slice(1) : imagePath

  return `${baseUrl}/storage/${cleanPath}`
}
