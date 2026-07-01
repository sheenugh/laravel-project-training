document.addEventListener('livewire:navigated', () => {
    if (typeof initFlowbite === 'function') {
        initFlowbite();
    }
});