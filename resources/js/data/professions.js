export async function rollProfession(race, diceRoll) {
    try {
        const response = await axios.get('/create-character/get-rolled-profession', {
            params: {
                race: race,
                rolledValue: diceRoll
            }
        });

        return response.data;
    } catch (error) {
        console.error(error);
        throw new Error(`Nie udało się pobrać profesji dla wyniku: ${diceRoll} i rasy ${race}`);
    }
}
