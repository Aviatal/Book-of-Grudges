// Czytelna etykieta konkretnego wariantu modyfikatora testu, spójna z oznaczeniami
// używanymi przy samym rzucie w sesji (½ dla połowy cechy, +N/-N dla modyfikatora).
export function formatModifierLabel(modifier: number, half: boolean): string {
    const modifierPart = modifier !== 0 ? (modifier > 0 ? `+${modifier}` : `${modifier}`) : null;

    if (half && modifierPart) {
        return `½ ${modifierPart}`;
    }
    if (half) {
        return 'Połowa cechy';
    }
    if (modifierPart) {
        return modifierPart;
    }

    return 'Bez modyfikatora';
}

export function modifierKey(modifier: number, half: boolean): string {
    return `${half ? 'half' : 'full'}:${modifier}`;
}
