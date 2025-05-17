function placeholderBehavior(){
    // Находим textarea по ID
    const textarea = document.getElementById("formMesssge");
    
    // Сохраняем оригинальный плейсхолдер
    const originalPlaceholder = textarea.placeholder;
    
    // Убираем плейсхолдер при фокусе
    textarea.addEventListener("focus", () => {
        textarea.placeholder = "";
    });
    
    // Восстанавливаем плейсхолдер при потере фокуса
    textarea.addEventListener("blur", () => {
        textarea.placeholder = originalPlaceholder;
    });
}

export default placeholderBehavior