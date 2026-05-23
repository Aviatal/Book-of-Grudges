export type DrawingLayerId = 'map' | 'gm';

export type DrawingData = {
    id: number;
    type: string;
    layer: DrawingLayerId;
    height: number;
    width: number;
    x: number;
    y: number;
    points: number[];
    strokeWidth: number;
    scaleX: number;
    scaleY: number;
    rotation: number;
    // Opcjonalne właściwości wizualne Konvy (przechowywane w data JSON)
    stroke?: string;
    fill?: string;
    tension?: number;
    lineCap?: string;
    lineJoin?: string;
    opacity?: number;
};
