export interface Message {
    id: number,
    user_id: number,
    recipient_id: number | null,
    author_name: string,
    text: string,
    type: 'chat' | 'roll' | 'skill_test' | 'dice_roll'
    created_at: string
}
