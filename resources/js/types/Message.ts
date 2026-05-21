export interface Message {
    id: number,
    user_id: number,
    author_name: string,
    text: string,
    type: 'chat' | 'roll' | 'skill_test'
    created_at: string
}
