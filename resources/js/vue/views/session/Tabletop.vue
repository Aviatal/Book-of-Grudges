<template>
    <div class="game-container">
        <div v-if="hasDrawingPermission" class="toolbar shadow-lg">
            <div class="tool-group">
                <button :class="{ active: activeTool === 'select' }" @click="activeTool = 'select'">🏹 Tokeny</button>
                <button :class="{ active: activeTool === 'select-draw' }" @click="activeTool = 'select-draw'">🛠️ Edytuj</button>
            </div>
            <div class="tool-group">
                <button :class="{ active: activeTool === 'pen' }" @click="activeTool = 'pen'">✏️ Pisak</button>
                <button :class="{ active: activeTool === 'rect' }" @click="activeTool = 'rect'">⬜ Prostokąt</button>
                <button :class="{ active: activeTool === 'eraser' }" @click="activeTool = 'eraser'">🧹 Gumka</button>
            </div>
            <button
                v-if="activeTool === 'select-draw' && selectedDrawingIds.length > 0"
                class="delete-selected-btn"
                @click="bulkDeleteSelectedDrawings"
            >🗑 Usuń zaznaczone ({{ selectedDrawingIds.length }})</button>
            <button :class="{ active: activeTool === 'ping' }" @click="activeTool = 'ping'">📍 Ping</button>
            <div v-if="hasDrawingPermission" class="tool-group">
                <button :class="{ active: activeTool === 'fog' }" @click="activeTool = 'fog'" title="Mgła Wojny">🌫️ Mgła</button>
            </div>
            <template v-if="hasDrawingPermission && activeTool === 'fog'">
                <div class="tool-group">
                    <button :class="{ active: fogMode === 'reveal' }" @click="fogMode = 'reveal'" title="Rysuj obszary widoczne dla graczy">🔍 Odkryj</button>
                    <button :class="{ active: fogMode === 'hide' }" @click="fogMode = 'hide'" title="Zaciemnij ponownie zaznaczony obszar">🌑 Zaciemnij</button>
                </div>
                <button @click="toggleFog" :class="{ active: fogEnabled }" :title="fogEnabled ? 'Wyłącz mgłę dla wszystkich' : 'Włącz mgłę dla wszystkich'">
                    {{ fogEnabled ? '🔒 Wyłącz' : '🔓 Włącz' }}
                </button>
                <button v-if="fogShapes.length > 0" @click="clearFogShapes" title="Usuń wszystkie obszary (odkryte i zaciemnione)">
                    🗑 Wyczyść ({{ fogShapes.length }})
                </button>
            </template>
            <button
                v-if="hasDrawingPermission && fogEnabled"
                @click="gmFogVisible = !gmFogVisible"
                :class="{ active: gmFogVisible }"
                :title="gmFogVisible ? 'Wróć do widoku MG (z mgłą)' : 'Podgląd pełnej mapy bez mgły'"
            >{{ gmFogVisible ? '🌑 Widok MG' : '👁️ Pełny widok' }}</button>
            <div class="zoom-control">
                <button class="zoom-btn" @click="stageScale = Math.min(8, stageScale * 1.12)" title="Powiększ">＋</button>
                <span class="zoom-label" @click="stageScale = 1; stagePos = { x: 0, y: 0 }" title="Kliknij, aby zresetować zoom">{{ Math.round(stageScale * 100) }}%</span>
                <button class="zoom-btn" @click="stageScale = Math.max(0.1, stageScale / 1.12)" title="Oddal">－</button>
            </div>
            <div v-if="activeTool === 'ping'" class="color-picker">
                <div
                    v-for="color in colors"
                    :key="color.value"
                    class="color-dot"
                    :style="{ backgroundColor: color.value, border: pingColor === color.value ? '2px solid white' : 'none' }"
                    @click="pingColor = color.value"
                ></div>
            </div>
        </div>

        <FloatingPanel
            panel-id="chat"
            title="📜 Komunikaty sesji"
            :default-pos="panelDefaults.chat.pos"
            :default-size="panelDefaults.chat.size"
            :min-width="260"
            :min-height="160"
        >
            <template #header-actions>
                <a
                    :href="`/karta-postaci/${heroId}`"
                    target="_blank"
                    rel="noopener"
                    class="hero-sheet-link"
                    title="Otwórz kartę bohatera w nowej karcie"
                >📋</a>
            </template>
            <div class="chat-messages" ref="messageContainer">
                <template v-for="msg in messages" :key="msg.id">
                    <div v-if="msg.type === 'roll'" class="message-roll-card">
                        <div class="roll-card-header">
                            <span class="roll-card-icon">🎲</span>
                            <span class="roll-card-type">INICJATYWA</span>
                            <span class="roll-card-time">{{ formatDate(msg.created_at) }}</span>
                        </div>
                        <div class="roll-card-author">{{ msg.author_name }}</div>
                        <div class="roll-card-breakdown">
                            <div class="roll-die">
                                <span class="roll-die-value">{{ parseRoll(msg.text).zr }}</span>
                                <span class="roll-die-label">Zręczność</span>
                            </div>
                            <span class="roll-op">+</span>
                            <div class="roll-die roll-die-d10">
                                <span class="roll-die-value">{{ parseRoll(msg.text).dice }}</span>
                                <span class="roll-die-label">k10</span>
                            </div>
                            <span class="roll-op">=</span>
                            <div class="roll-die roll-die-total">
                                <span class="roll-die-value">{{ parseRoll(msg.text).total }}</span>
                                <span class="roll-die-label">Wynik</span>
                            </div>
                        </div>
                    </div>
                    <div v-else-if="msg.type === 'skill_test'" class="message-skill-card" :class="parseSkillTest(msg.text)?.passed ? 'skill-passed' : 'skill-failed'">
                        <div class="skill-card-header">
                            <span class="skill-card-icon">🎯</span>
                            <span class="skill-card-type">TEST UMIEJĘTNOŚCI</span>
                            <span class="skill-card-time">{{ formatDate(msg.created_at) }}</span>
                        </div>
                        <div class="skill-card-author">{{ msg.author_name }}</div>
                        <div class="skill-card-name">
                            {{ parseSkillTest(msg.text)?.skill }}
                            <span class="skill-card-char">({{ parseSkillTest(msg.text)?.characteristic }})</span>
                        </div>
                        <div class="skill-card-effective" v-if="parseSkillTest(msg.text)?.half || parseSkillTest(msg.text)?.modifier !== 0">
                            <span class="eff-base">{{ parseSkillTest(msg.text)?.characteristic_value }}</span>
                            <span class="eff-op" v-if="parseSkillTest(msg.text)?.half">÷2</span>
                            <span class="eff-op" v-if="parseSkillTest(msg.text)?.modifier !== 0">
                                {{ parseSkillTest(msg.text)?.modifier > 0 ? '+' + parseSkillTest(msg.text)?.modifier : parseSkillTest(msg.text)?.modifier }}
                            </span>
                            <span class="eff-sep">=</span>
                            <span class="eff-result">{{ parseSkillTest(msg.text)?.effective_value }}</span>
                        </div>
                        <div class="skill-card-breakdown">
                            <div class="skill-stat">
                                <span class="skill-stat-value">{{ parseSkillTest(msg.text)?.effective_value ?? parseSkillTest(msg.text)?.characteristic_value }}</span>
                                <span class="skill-stat-label">Próg</span>
                            </div>
                            <span class="skill-vs">vs</span>
                            <div class="skill-stat">
                                <span class="skill-stat-value">{{ parseSkillTest(msg.text)?.roll }}</span>
                                <span class="skill-stat-label">k100</span>
                            </div>
                            <div class="skill-verdict" :class="parseSkillTest(msg.text)?.passed ? 'skill-verdict-pass' : 'skill-verdict-fail'">
                                {{ parseSkillTest(msg.text)?.passed ? '✓ ZDANY' : '✗ NIEZDANY' }}
                            </div>
                        </div>
                    </div>
                    <div v-else class="message">
                        <span class="msg-author">[{{ msg.author_name }}]</span>
                        <span class="msg-content">{{ msg.text }}</span>
                        <span class="msg-time">{{ formatDate(msg.created_at) }}</span>
                    </div>
                </template>
            </div>

            <div v-if="isRolling" class="dice-overlay">
                <div class="dice-overlay-die">🎲</div>
                <div class="dice-overlay-label">Rzut na inicjatywę...</div>
            </div>

            <div class="chat-input-area">
                <input
                    v-model="newMessage"
                    @keyup.enter="sendMessage"
                    placeholder="Napisz wiadomość..."
                    type="text"
                />
                <button @click="sendMessage">➤</button>
            </div>
            <div class="chat-actions">
                <button class="roll-btn" @click="toggleSkillPicker" :disabled="isRollingSkill" :class="{ active: showSkillPicker }">
                    🎯 Test umiejętności
                </button>
            </div>

            <div v-if="showSkillPicker" class="skill-picker">
                <div class="skill-picker-modifiers">
                    <button
                        v-for="mod in MODIFIERS"
                        :key="mod"
                        class="mod-btn"
                        :class="{ 'mod-active': skillModifier === mod, 'mod-neg': mod < 0, 'mod-pos': mod > 0, 'mod-zero': mod === 0 }"
                        @click="skillModifier = mod"
                    >{{ mod > 0 ? '+' + mod : mod }}</button>
                </div>
                <div class="skill-picker-options">
                    <button
                        class="half-btn"
                        :class="{ 'half-active': skillHalf }"
                        @click="skillHalf = !skillHalf"
                    >½ Połowa cechy</button>
                </div>
                <input
                    v-model="skillSearch"
                    class="skill-picker-search"
                    placeholder="Szukaj umiejętności..."
                    type="text"
                />
                <div class="skill-picker-list">
                    <template v-if="filteredSkills.length">
                        <div v-if="filteredSkills.some(s => s.is_purchased)" class="skill-group-label">Wykupione</div>
                        <button
                            v-for="skill in filteredSkills.filter(s => s.is_purchased)"
                            :key="skill.id"
                            class="skill-item skill-item-purchased"
                            @click="rollSkill(skill.id)"
                        >
                            <span class="skill-item-name">{{ skill.additional_name ?? skill.name }}</span>
                            <span class="skill-item-char">{{ skill.characteristic }} {{ skill.characteristic_value }}</span>
                        </button>
                        <div v-if="filteredSkills.some(s => !s.is_purchased)" class="skill-group-label">Pozostałe</div>
                        <button
                            v-for="skill in filteredSkills.filter(s => !s.is_purchased)"
                            :key="skill.id"
                            class="skill-item"
                            @click="rollSkill(skill.id)"
                        >
                            <span class="skill-item-name">{{ skill.name }}</span>
                            <span class="skill-item-char">{{ skill.characteristic }} {{ skill.characteristic_value }}</span>
                        </button>
                    </template>
                    <div v-else-if="isLoadingSkills" class="skill-picker-info">Ładowanie...</div>
                    <div v-else class="skill-picker-info">Brak wyników</div>
                </div>
            </div>
        </FloatingPanel>

        <!-- Panel warstw -->
        <FloatingPanel
            v-if="hasDrawingPermission"
            panel-id="layers"
            title="🗂 Warstwy"
            :default-pos="{ x: 10, y: 62 }"
            :default-size="{ w: 220, h: 320 }"
            :min-width="180"
            :min-height="120"
        >
            <div class="layer-list-body">
            <div
                v-for="layer in mapLayers"
                :key="layer.id"
                class="layer-row"
                :class="{ 'layer-active': activeLayerId === layer.id }"
                @click="setActiveLayer(layer.id)"
            >
                <span
                    class="layer-color-dot"
                    :style="{ backgroundColor: layer.color }"
                ></span>
                <span class="layer-name">{{ layer.name }}</span>
                <div class="layer-actions" @click.stop>
                    <button
                        class="layer-icon-btn"
                        :title="layer.visible ? 'Ukryj' : 'Pokaż'"
                        @click="layer.visible = !layer.visible"
                    >{{ layer.visible ? '👁' : '🚫' }}</button>
                    <button
                        class="layer-icon-btn"
                        :title="layer.locked ? 'Odblokuj' : 'Zablokuj'"
                        @click="layer.locked = !layer.locked"
                    >{{ layer.locked ? '🔒' : '🔓' }}</button>
                    <button
                        v-if="selectedShapeId !== null && activeLayerId !== layer.id"
                        class="layer-move-btn"
                        title="Przenieś zaznaczony element tutaj"
                        @click="moveSelectedDrawingToLayer(layer.id)"
                    >📤</button>
                </div>
            </div>

            <div class="layers-divider"></div>

            <!-- Warstwa tokenów (zawsze widoczna) -->
            <div
                class="layer-row"
                :class="{ 'layer-active': activeLayerId === 'tokens' }"
                @click="setActiveLayer('tokens')"
            >
                <span class="layer-color-dot" style="background:#d4af37"></span>
                <span class="layer-name">🏹 Tokeny</span>
                <div class="layer-actions" @click.stop>
                    <button
                        class="layer-icon-btn"
                        :title="tokenLayerVisible ? 'Ukryj' : 'Pokaż'"
                        @click="tokenLayerVisible = !tokenLayerVisible"
                    >{{ tokenLayerVisible ? '👁' : '🚫' }}</button>
                    <button
                        class="layer-icon-btn"
                        :title="tokenLayerLocked ? 'Odblokuj' : 'Zablokuj'"
                        @click="tokenLayerLocked = !tokenLayerLocked"
                    >{{ tokenLayerLocked ? '🔒' : '🔓' }}</button>
                </div>
            </div>
            </div><!-- /layer-list-body -->
        </FloatingPanel>

        <!-- Schowek -->
        <FloatingPanel
            v-if="hasDrawingPermission"
            panel-id="stash"
            :title="`🗃 Schowek${assets.length ? ' (' + assets.length + ')' : ''}`"
            :default-pos="panelDefaults.stash.pos"
            :default-size="{ w: 360, h: 320 }"
            :min-width="240"
            :min-height="120"
            start-minimized
        >
            <div class="stash-body">
                <div class="stash-upload">
                    <select v-model="uploadAssetType" class="stash-type-select">
                        <option value="image">🖼 Obraz</option>
                        <option value="map">🗺 Mapa</option>
                        <option value="token">🏹 Token</option>
                    </select>
                    <label class="stash-upload-btn" :class="{ 'stash-uploading': isUploadingAsset }">
                        {{ isUploadingAsset ? '⏳' : '+ Dodaj plik' }}
                        <input type="file" accept="image/*" style="display:none" :disabled="isUploadingAsset" @change="uploadAsset" />
                    </label>
                </div>
                <div class="stash-grid">
                    <div
                        v-for="asset in assets"
                        :key="asset.id"
                        class="stash-item"
                        :title="asset.name"
                        draggable="true"
                        @dragstart="(e) => onAssetDragStart(e, asset)"
                    >
                        <img :src="asset.file_url" class="stash-thumb" :alt="asset.name" />
                        <div class="stash-item-footer">
                            <span class="stash-item-name">{{ asset.name }}</span>
                            <button class="stash-item-delete" @click.stop="deleteAsset(asset)" title="Usuń">✕</button>
                        </div>
                        <span class="stash-type-badge">{{ asset.type }}</span>
                    </div>
                    <div v-if="assets.length === 0" class="stash-empty">Brak zasobów</div>
                </div>
            </div>
        </FloatingPanel>

        <!-- Schowek tokenów NPC -->
        <FloatingPanel
            v-if="hasDrawingPermission && npcTokens.length > 0"
            panel-id="npc-stash"
            :title="`🧟 Tokeny NPC (${npcTokens.length})`"
            :default-pos="panelDefaults.npcStash.pos"
            :default-size="{ w: 300, h: 420 }"
            :min-width="220"
            :min-height="120"
            start-minimized
        >
            <div class="npc-stash-body">
                <input
                    v-model="npcSearch"
                    class="npc-search"
                    placeholder="🔍 Szukaj..."
                    @click.stop
                />
                <div class="npc-stash-scroll">
                    <div
                        v-for="token in filteredNpcTokens"
                        :key="token.id"
                        class="npc-token-item"
                        :class="{ 'npc-on-map': token.on_map }"
                        :draggable="!token.on_map"
                        :title="token.name"
                        @dragstart="(e) => !token.on_map && onNpcTokenDragStart(e, token)"
                    >
                        <div class="npc-token-avatar">
                            <img v-if="token.image" :src="token.image_url" :alt="token.name" />
                            <span v-else class="npc-token-placeholder">🧟</span>
                        </div>
                        <div class="npc-token-info">
                            <span class="npc-token-name">{{ token.name }}</span>
                            <span v-if="token.on_map" class="npc-on-map-badge">📍 Na mapie</span>
                        </div>
                        <div class="npc-token-actions">
                            <button
                                class="npc-action-btn"
                                title="Podgląd karty postaci"
                                @click.stop="selectedNpcToken = token"
                            >📋</button>
                            <button
                                class="npc-action-btn"
                                title="Duplikuj token"
                                @click.stop="duplicateNpcToken(token)"
                            >⧉</button>
                            <button
                                v-if="token.on_map"
                                class="npc-remove-btn"
                                title="Zdejmij z mapy"
                                @click.stop="removeNpcTokenFromMap(token)"
                            >✕</button>
                        </div>
                    </div>
                    <div v-if="filteredNpcTokens.length === 0" class="stash-empty">Brak wyników</div>
                </div>
            </div>
        </FloatingPanel>

        <div class="stage-wrapper" :class="{ 'cursor-grab': isPanning }" @dragover="onCanvasDragOver" @drop="onCanvasDrop">
        <v-stage
            ref="stageRef"
            :config="stageConfig"
            @mousedown="handleStageMouseDown"
            @mousemove="handleStageMouseMove"
            @mouseup="handleStageMouseUp"
            @wheel="handleWheel"
        >
            <!-- Osobna v-layer dla każdej warstwy rysunków -->
            <v-layer
                v-for="layer in mapLayers"
                :key="layer.id"
                :config="{ visible: layer.visible }"
            >
                <template v-for="draw in drawingsByLayer[layer.id]" :key="draw.id">
                    <v-image
                        v-if="draw.type === 'image' && loadedDrawingImages[draw.id]"
                        :config="{
                            ...draw,
                            id: String(draw.id),
                            image: loadedDrawingImages[draw.id],
                            stroke: selectedDrawingIds.includes(draw.id) ? '#00a1ff' : undefined,
                            strokeWidth: selectedDrawingIds.includes(draw.id) ? 3 : 0,
                            draggable: !layer.locked && (activeTool === 'select-draw' || activeTool === 'select')
                        }"
                        @click="(e) => handleShapeClick(e, draw.id, layer.id)"
                        @dragmove="(e) => handleImageDragMove(e, draw)"
                        @transformend="(e) => handleTransformEnd(e, draw)"
                        @dragend="(e) => handleImageDragEnd(e, draw)"
                    />
                    <v-text
                        v-if="draw.type === 'image' && draw.label && loadedDrawingImages[draw.id]"
                        :config="{
                            text: draw.label,
                            x: draw.x,
                            y: draw.y + (draw.height ?? 0) * (draw.scaleY ?? 1) + 4,
                            width: (draw.width ?? 100) * (draw.scaleX ?? 1),
                            align: 'center',
                            fontSize: 12,
                            fill: 'white',
                            fontStyle: 'bold',
                            shadowColor: 'black',
                            shadowBlur: 4,
                            listening: false
                        }"
                    />
                    <v-line
                        v-if="draw.type === 'pen'"
                        :config="{
                            ...draw,
                            id: String(draw.id),
                            stroke: selectedDrawingIds.includes(draw.id) ? '#00a1ff' : draw.stroke,
                            strokeWidth: selectedDrawingIds.includes(draw.id) ? (draw.strokeWidth ?? 3) + 2 : draw.strokeWidth,
                            draggable: !layer.locked && activeTool === 'select-draw' && selectedDrawingIds.length <= 1
                        }"
                        @click="(e) => handleShapeClick(e, draw.id, layer.id)"
                        @dragend="(e) => handleTransformEnd(e, draw)"
                    />
                    <v-rect
                        v-if="draw.type === 'rect'"
                        :config="{
                            ...draw,
                            id: String(draw.id),
                            stroke: selectedDrawingIds.includes(draw.id) ? '#00a1ff' : draw.stroke,
                            strokeWidth: selectedDrawingIds.includes(draw.id) ? (draw.strokeWidth ?? 2) + 2 : draw.strokeWidth,
                            draggable: !layer.locked && activeTool === 'select-draw' && selectedDrawingIds.length <= 1
                        }"
                        @click="(e) => handleShapeClick(e, draw.id, layer.id)"
                        @transformend="(e) => handleTransformEnd(e, draw)"
                        @dragend="(e) => handleTransformEnd(e, draw)"
                    />
                </template>
            </v-layer>

            <!-- Warstwa Mgły Wojny -->
            <v-layer
                v-if="fogEnabled && (!hasDrawingPermission || !gmFogVisible)"
                :config="{ opacity: hasDrawingPermission ? 0.6 : 1, listening: false }"
            >
                <!-- Pełne pokrycie mgłą -->
                <v-rect
                    :config="{
                        x: -50000, y: -50000,
                        width: 100000, height: 100000,
                        fill: 'black',
                        listening: false
                    }"
                />
                <!-- Kształty mgły w kolejności tworzenia — reveal wycina dziury, hide zapełnia je z powrotem -->
                <v-rect
                    v-for="area in fogShapesOrdered"
                    :key="area.id"
                    :config="{
                        x: area.x,
                        y: area.y,
                        width: area.width * (area.scaleX ?? 1),
                        height: area.height * (area.scaleY ?? 1),
                        fill: 'rgba(0,0,0,1)',
                        globalCompositeOperation: area.type === 'fog' ? 'destination-out' : 'source-over',
                        listening: false
                    }"
                />
            </v-layer>

            <!-- Warstwa tokenów -->
            <v-layer
                :config="{ visible: tokenLayerVisible }"
                @dragmove="handleTokenLayerDragMove"
                @dragend="handleGroupDragEnd"
            >
                <v-group
                    v-for="token in mapTokens"
                    :key="token.id"
                    :config="{
                        id: String(token.id),
                        x: token.x,
                        y: token.y,
                        draggable: hasDrawingPermission && !tokenLayerLocked && activeLayerId === 'tokens'
                    }"
                >
                    <v-circle v-if="selectedIds.includes(token.id)" :config="{
                        radius: 55,
                        fill: '#d4af37',
                        opacity: 0.4
                    }" />
                    <v-image v-if="loadedImages[token.id]" :config="{
                        image: loadedImages[token.id],
                        width: 100,
                        height: 100,
                        x: -50,
                        y: -50,
                        clipFunc: (ctx: CanvasRenderingContext2D) => {
                            ctx.arc(50, 50, 50, 0, Math.PI * 2, false);
                        }
                    }" />
                    <v-circle :config="{
                        radius: 50,
                        stroke: selectedIds.includes(token.id) ? '#00ff00' : (props.heroId === token.hero_id ? '#d4af37' : 'black'),
                        strokeWidth: selectedIds.includes(token.id) ? 5 : 3
                    }" />
                    <v-text :config="{
                        text: token.name,
                        fontSize: 12,
                        x: -50,
                        y: 60,
                        width: 100,
                        align: 'center',
                        fill: 'white',
                        fontStyle: 'bold',
                        shadowColor: 'black',
                        shadowBlur: 5
                    }" />
                    <!-- Znaczek karty postaci — tylko NPC z wypełnioną kartą, tylko dla MG -->
                    <v-text v-if="hasDrawingPermission && !token.hero_id && token.sheet" :config="{
                        text: '📋',
                        fontSize: 14,
                        x: 32,
                        y: -58,
                        listening: false
                    }" />
                </v-group>

                <v-rect v-if="selectionBox.visible" :config="{
                    x: selectionBox.x,
                    y: selectionBox.y,
                    width: selectionBox.width,
                    height: selectionBox.height,
                    fill: 'rgba(0, 161, 255, 0.3)',
                    stroke: '#00a1ff',
                    strokeWidth: 1
                }" />
            </v-layer>

            <!-- Warstwa UI: transformer + selection box + pingi (zawsze na wierzchu) -->
            <v-layer>
                <v-transformer
                    ref="transformerNode"
                    :config="{
                        visible: activeTool === 'select-draw' && selectedDrawingIds.length === 1 && hasDrawingPermission,
                        enabledAnchors: ['top-center','top-left','middle-left','bottom-left','bottom-center','bottom-right','middle-right','top-right']
                    }"
                />
                <!-- Selection box dla rysunków -->
                <v-rect v-if="drawingSelectionBox.visible" :config="{
                    x: drawingSelectionBox.x,
                    y: drawingSelectionBox.y,
                    width: drawingSelectionBox.width,
                    height: drawingSelectionBox.height,
                    fill: 'rgba(0, 161, 255, 0.15)',
                    stroke: '#00a1ff',
                    strokeWidth: 1,
                    dash: [6, 3]
                }" />
                <PingItem
                    v-for="ping in pings"
                    :key="ping.id"
                    :x="ping.x"
                    :y="ping.y"
                    :color="ping.color"
                />
            </v-layer>
        </v-stage>
        </div>
    </div>

    <!-- Karta postaci NPC (GM) -->
    <NpcSheetPopup
        v-if="selectedNpcToken && hasDrawingPermission"
        :token="selectedNpcToken"
        :can-roll="true"
        @close="selectedNpcToken = null"
    />

    <!-- Tracker walki -->
    <CombatTracker
        :map-token-ids="mapTokens.map(t => t.id)"
        :hero-id="props.heroId"
        :has-drawing-permission="props.hasDrawingPermission"
    />
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import axios from 'axios';
import {Token} from "@/types/Token";
import {DrawingData, DrawingLayerId} from "@/types/DrawingData";
import {Message} from "@/types/Message";
import PingItem from '../../components/session/PingItem.vue';
import NpcSheetPopup from '../../components/session/NpcSheetPopup.vue';
import CombatTracker from '../../components/session/CombatTracker.vue';
import FloatingPanel from '../../components/session/FloatingPanel.vue';

const props = defineProps<{
    heroId: number,
    hasDrawingPermission: boolean
}>();

interface MoveTokenEvent {
    id: number;
    x: number;
    y: number;
}
interface DrawingEditEvent {
    drawingId: number;
    data: DrawingData
}

interface PingData {
    id: number
    x: number,
    y: number,
    color: number,
}

interface Asset {
    id: number;
    name: string;
    type: 'map' | 'token' | 'image';
    file_url: string;
}

interface MapLayer {
    id: DrawingLayerId;
    name: string;
    color: string;
    visible: boolean;
    locked: boolean;
}

    window.Echo.channel('token-move')
    .listen('.move', (e: MoveTokenEvent) => {
        moveToken(e.id, e.x, e.y);
    })
    .listen('.batch-move', (e: { tokens: MoveTokenEvent[] }) => {
        console.log('Received batch move event:', e);
        e.tokens.forEach(token => {
            moveToken(token.id, token.x, token.y);
        })
    })
    .listen('.token-placed', (e: { id: number; x: number; y: number }) => {
        const token = tokens.value.find(t => t.id === e.id);
        if (token) { token.x = e.x; token.y = e.y; token.on_map = true; }
    })
    .listen('.token-removed-from-map', (e: { id: number }) => {
        const token = tokens.value.find(t => t.id === e.id);
        if (token) { token.on_map = false; }
    });
window.Echo.channel('drawings')
    .listen('.drawing-update', (e: DrawingEditEvent) => {
        let drawing = drawings.value.find(d => d.id === e.drawingId);
        if (drawing) {
            Object.assign(drawing, e.data);
        }
    })
    .listen('.drawing-create', (e: { id: number; type: string; data: DrawingData; layer: DrawingLayerId }) => {
        const drawing: DrawingData = { ...e.data, id: e.id, type: e.type, layer: e.layer === 'gm' ? 'gm' : 'map' };
        drawings.value.push(drawing);
        loadDrawingImage(drawing);
    })
    .listen('.drawing-delete', (e: { drawingId: number }) => {
        drawings.value = drawings.value.filter(d => d.id !== e.drawingId);
    })
    .listen('.drawing-layer-changed', (e: { drawingId: number; layer: DrawingLayerId }) => {
        const drawing = drawings.value.find(d => d.id === e.drawingId);
        if (drawing) {
            drawing.layer = e.layer;
        }
    })
    .listen('.ping', (e: { newPing: PingData }) => {
        const newPing = {
            id: Date.now(),
            x: e.newPing.x,
            y: e.newPing.y,
            color: pingColor.value
        };

        createPing(newPing);
    });
// todo:: Webhooks
window.Echo.channel('session-chat')
    .listen('.message-sent', (e: any) => {
        messages.value.push(e.message);
        scrollToBottom();
    });

const moveToken = (tokenId: number, x: number, y: number) => {
    const token = tokens.value.find(t => t.id === tokenId);
    if (token) {
        token.x = x;
        token.y = y;
    }
}

const tokens = ref<Token[]>([]);
const loadedImages = ref<Record<number, HTMLImageElement>>({});
const activeTool = ref<'select' | 'pen' | 'rect' | 'circle' | 'select-draw' | 'eraser' | 'ping' | 'fog'>('select');
const drawings = ref<DrawingData[]>([]);
const isDrawing = ref(false);
const history = ref<any[]>([]);
const selectedShapeId = ref<number | null>(null);
const selectedDrawingIds = ref<number[]>([]);
const drawingSelectionBox = ref({ x: 0, y: 0, width: 0, height: 0, visible: false });
const assets = ref<Asset[]>([]);
// Domyślne pozycje paneli — przy krawędziach ekranu, obliczone raz przy starcie
const W = window.innerWidth;
const H = window.innerHeight;
const panelDefaults = {
    // Czat: prawy dolny róg
    chat:     { pos: { x: W - 360, y: H - 450 }, size: { w: 350, h: 440 } },
    // Schowek assetów: prawy górny róg (zwinięty domyślnie)
    stash:    { pos: { x: W - 370, y: 10 },       size: { w: 360, h: 300 } },
    // Schowek NPC: lewy górny róg
    npcStash: { pos: { x: 10, y: 50 },            size: { w: 280, h: 420 } },
};

const isUploadingAsset = ref(false);
const uploadAssetType = ref<Asset['type']>('image');
const npcSearch = ref('');
const selectedNpcToken = ref<Token | null>(null);
const loadedDrawingImages = ref<Record<number, HTMLImageElement>>({});
const pingColor = ref('#00a1ff');
const pings = ref<any[]>([]);
interface SkillTestResult {
    skill: string;
    characteristic: string;
    characteristic_value: number;
    effective_value: number;
    modifier: number;
    half: boolean;
    roll: number;
    passed: boolean;
}

interface SkillOption {
    id: number;
    name: string;
    type: string;
    characteristic: string;
    characteristic_value: number;
    is_purchased: boolean;
    additional_name: string | null;
}

const messages = ref<Message[]>([]);
const newMessage = ref('');
const isRolling = ref(false);
const isRollingSkill = ref(false);
const showSkillPicker = ref(false);
const skillSearch = ref('');
const skillModifier = ref(0);
const skillHalf = ref(false);
const skills = ref<SkillOption[]>([]);
const isLoadingSkills = ref(false);
const MODIFIERS = [-40, -30, -20, -10, 0, 10, 20, 30, 40];
const messageContainer = ref<HTMLElement | null>(null);

const filteredSkills = computed(() => {
    const q = skillSearch.value.trim().toLowerCase();
    return skills.value.filter(s =>
        !q || s.name.toLowerCase().includes(q) || (s.additional_name ?? '').toLowerCase().includes(q)
    );
});

const mapTokens  = computed(() => tokens.value.filter(t => t.on_map));
const npcTokens  = computed(() => tokens.value.filter(t => !t.hero_id));
const filteredNpcTokens = computed(() => {
    const q = npcSearch.value.trim().toLowerCase();
    return q ? npcTokens.value.filter(t => t.name.toLowerCase().includes(q)) : npcTokens.value;
});

const drawingsByLayer = computed<Record<DrawingLayerId, DrawingData[]>>(() => {
    const result: Record<DrawingLayerId, DrawingData[]> = {
        map: [],
        gm: [],
    };
    drawings.value.forEach((d: DrawingData) => {
        if (d.type === 'fog' || d.type === 'fog_meta' || d.type === 'fog_hide') return;
        const layerId: DrawingLayerId = (d.layer === 'gm') ? 'gm' : 'map';
        result[layerId].push(d);
    });
    return result;
});

const fogEnabled = computed(() => drawings.value.some(d => d.type === 'fog_meta'));
const fogRevealShapes = computed(() => drawings.value.filter(d => d.type === 'fog'));
const fogHideShapes = computed(() => drawings.value.filter(d => d.type === 'fog_hide'));
const fogShapes = computed(() => drawings.value.filter(d => d.type === 'fog' || d.type === 'fog_hide'));
const fogShapesOrdered = computed(() =>
    fogShapes.value.slice().sort((a, b) => a.id - b.id)
);

const colors = [
    { name: 'Niebieski', value: '#00a1ff' },
    { name: 'Czerwony', value: '#ff4d4d' },
    { name: 'Zielony', value: '#2ecc71' },
    { name: 'Złoty', value: '#d4af37' }
];

const transformerNode = ref();
const stageRef = ref();
const mapLayers = ref<MapLayer[]>([
    { id: 'map', name: '🗺 Mapa', color: '#2e7d32', visible: true, locked: false },
    { id: 'gm', name: '👁 Warstwa MG', color: '#8e24aa', visible: true, locked: false },
]);
const activeLayerId = ref<DrawingLayerId | 'tokens'>('map');
const tokenLayerVisible = ref(true);
const tokenLayerLocked = ref(false);

const fetchDrawings = async () => {
    const { data } = await axios.get('/session/drawings');
    drawings.value = data.map((d: any): DrawingData => ({
        ...d.data,
        // id i layer muszą być PO spreadzie, żeby nie zostały nadpisane przez d.data
        id: d.id,
        type: d.type,
        layer: (d.layer === 'gm' ? 'gm' : 'map') as DrawingLayerId,
    }));
    drawings.value.forEach(loadDrawingImage);
};

const fetchTokens = async () => {
    const response = await axios.get('/session/tokens');
    tokens.value = response.data;

    tokens.value.forEach(token => {
        loadImage(token);
    });
};

const fetchMessages = async () => {
    const { data } = await axios.get('/session/chat');
    messages.value = data;
    scrollToBottom();
};

const loadDrawingImage = (drawing: DrawingData): void => {
    if (drawing.type !== 'image' || !drawing.src || loadedDrawingImages.value[drawing.id]) return;
    const img = new window.Image();
    img.src = drawing.src;
    img.onload = () => { loadedDrawingImages.value[drawing.id] = img; };
};

const fetchAssets = async (): Promise<void> => {
    const { data } = await axios.get<Asset[]>('/session/assets');
    assets.value = data;
};

const uploadAsset = async (e: Event): Promise<void> => {
    const input = e.target as HTMLInputElement;
    const file = input.files?.[0];
    if (!file) return;
    isUploadingAsset.value = true;
    try {
        const form = new FormData();
        form.append('file', file);
        form.append('type', uploadAssetType.value);
        form.append('name', file.name.replace(/\.[^/.]+$/, ''));
        const { data } = await axios.post<Asset>('/panel/assets/upload', form, {
            headers: { 'Content-Type': 'multipart/form-data' },
        });
        assets.value.unshift(data);
    } catch (error: unknown) {
        console.error('Błąd uploadu pliku', error);
    } finally {
        isUploadingAsset.value = false;
        input.value = '';
    }
};

const deleteAsset = async (asset: Asset): Promise<void> => {
    try {
        await axios.delete(`/panel/assets/${asset.id}`);
        assets.value = assets.value.filter(a => a.id !== asset.id);
    } catch (error: unknown) {
        console.error('Błąd usuwania assetu', error);
    }
};

const onNpcTokenDragStart = (e: DragEvent, token: Token): void => {
    e.dataTransfer?.setData('application/npc-token', JSON.stringify({ id: token.id }));
};

const placeNpcToken = async (tokenId: number, x: number, y: number): Promise<void> => {
    const token = tokens.value.find(t => t.id === tokenId);
    if (!token) return;
    token.x = x;
    token.y = y;
    token.on_map = true;
    loadImage(token);
    try {
        await axios.patch(`/session/tokens/${tokenId}/place`, { x, y });
    } catch (error: unknown) {
        console.error('Błąd umieszczania tokenu NPC', error);
        token.on_map = false;
    }
};

const duplicateNpcToken = async (token: Token): Promise<void> => {
    try {
        const { data } = await axios.post<Token>(`/session/tokens/${token.id}/duplicate`);
        tokens.value.push(data);
    } catch (error: unknown) {
        console.error('Błąd duplikowania tokenu NPC', error);
    }
};

const removeNpcTokenFromMap = async (token: Token): Promise<void> => {
    token.on_map = false;
    try {
        await axios.patch(`/session/tokens/${token.id}/remove-from-map`);
    } catch (error: unknown) {
        console.error('Błąd usuwania tokenu z mapy', error);
        token.on_map = true;
    }
};

const onAssetDragStart = (e: DragEvent, asset: Asset): void => {
    e.dataTransfer?.setData('application/asset', JSON.stringify({ id: asset.id, url: asset.file_url, type: asset.type, name: asset.name }));
};

const onCanvasDragOver = (e: DragEvent): void => { e.preventDefault(); };

const onCanvasDrop = async (e: DragEvent): Promise<void> => {
    e.preventDefault();

    // Upuszczenie tokenu NPC ze schowka
    const npcRaw = e.dataTransfer?.getData('application/npc-token');
    if (npcRaw) {
        const { id } = JSON.parse(npcRaw) as { id: number };
        const stage = stageRef.value?.getNode();
        if (!stage) return;
        const stageBox = stage.container().getBoundingClientRect();
        const dropX = (e.clientX - stageBox.left  - stagePos.value.x) / stageScale.value;
        const dropY = (e.clientY - stageBox.top   - stagePos.value.y) / stageScale.value;
        await placeNpcToken(id, Math.round(dropX), Math.round(dropY));
        return;
    }

    const raw = e.dataTransfer?.getData('application/asset');
    if (!raw) return;

    const { url, type, name } = JSON.parse(raw) as { id: number; url: string; type: Asset['type']; name: string };
    const stage = stageRef.value?.getNode();
    if (!stage) return;

    const stageBox = stage.container().getBoundingClientRect();
    const screenX  = e.clientX - stageBox.left;
    const screenY  = e.clientY - stageBox.top;
    const dropX    = (screenX - stagePos.value.x) / stageScale.value;
    const dropY    = (screenY - stagePos.value.y) / stageScale.value;

    const img = new window.Image();
    img.src = url;
    await new Promise<void>(resolve => { img.onload = () => resolve(); });

    const defaultWidth = type === 'map' ? 800 : type === 'token' ? 100 : 300;
    const scale = defaultWidth / img.naturalWidth;
    const w = Math.round(img.naturalWidth * scale);
    const h = Math.round(img.naturalHeight * scale);

    const layerId: DrawingLayerId = activeLayerId.value === 'tokens' ? 'map' : activeLayerId.value;
    const shapeData = {
        src: url,
        label: name,
        x: Math.round(dropX - w / 2),
        y: Math.round(dropY - h / 2),
        width: w,
        height: h,
        points: [] as number[],
        strokeWidth: 0,
        scaleX: 1,
        scaleY: 1,
        rotation: 0,
    };

    try {
        const { data } = await axios.post<{ id: number }>('/session/drawings/store', {
            type: 'image',
            layer: layerId,
            data: shapeData,
        });
        const drawing: DrawingData = { ...shapeData, id: data.id, type: 'image', layer: layerId };
        drawings.value.push(drawing);
        loadedDrawingImages.value[data.id] = img;
        // Po upuszczeniu: aktywuj właściwą warstwę i narzędzie edycji,
        // żeby można od razu klikać i przesuwać wstawiony obraz
        activeLayerId.value = layerId;
        activeTool.value = 'select-draw';
    } catch (error: unknown) {
        console.error('Błąd zapisu obrazu na mapie', error);
    }
};

const handleShapeClick = (e: any, shapeId: number, layerId: DrawingLayerId) => {
    const layer = mapLayers.value.find(l => l.id === layerId);

    // 1. Logika GUMKI
    if (activeTool.value === 'eraser') {
        if (layer?.locked) return;
        drawings.value = drawings.value.filter(d => d.id !== shapeId);
        selectedShapeId.value = null;
        transformerNode.value.getNode().nodes([]);
        axios.delete(`/session/drawings/${shapeId}`).catch((error: unknown) => {
            console.error('Błąd usuwania rysunku', error);
        });
        return;
    }

    // 2. Logika EDYCJI / ZAZNACZANIA
    if (activeTool.value === 'select-draw') {
        // Kliknięcie zaznacza rysunek niezależnie od aktywnej warstwy.
        // Automatycznie przełącz aktywną warstwę na tę, do której należy rysunek,
        // żeby drag i transformer działały poprawnie.
        if (layer && layer.locked) return;
        if (layer && activeLayerId.value !== layer.id) {
            activeLayerId.value = layer.id;
        }

        if (e.evt?.shiftKey) {
            // Shift+klik — toggle w multi-selekcji
            if (selectedDrawingIds.value.includes(shapeId)) {
                selectedDrawingIds.value = selectedDrawingIds.value.filter(id => id !== shapeId);
            } else {
                selectedDrawingIds.value.push(shapeId);
            }
            // Przy multi-selekcji chowamy transformer
            selectedShapeId.value = null;
            if (transformerNode.value) {
                transformerNode.value.getNode().nodes([]);
            }
        } else {
            // Zwykły klik — singleselect + transformer
            selectedDrawingIds.value = [shapeId];
            selectedShapeId.value = shapeId;
            transformerNode.value.getNode().nodes([e.target]);
            transformerNode.value.getNode().getLayer().batchDraw();
        }
    }
};

const canEditLayer = (layer: MapLayer): boolean => {
    return !layer.locked && activeLayerId.value === layer.id;
};

const setActiveLayer = (layerId: DrawingLayerId | 'tokens'): void => {
    activeLayerId.value = layerId;
    selectedShapeId.value = null;
    selectedDrawingIds.value = [];
    if (transformerNode.value) {
        transformerNode.value.getNode().nodes([]);
    }
};

const moveSelectedDrawingToLayer = async (targetLayerId: DrawingLayerId): Promise<void> => {
    if (selectedShapeId.value === null) return;
    const drawingId = selectedShapeId.value;
    try {
        await axios.patch(`/session/drawings/${drawingId}/layer`, { layer: targetLayerId });
        const drawing = drawings.value.find(d => d.id === drawingId);
        if (drawing) {
            drawing.layer = targetLayerId;
        }
    } catch (error: unknown) {
        console.error('Błąd przenoszenia rysunku do warstwy', error);
    }
};

const clearDrawingSelection = (): void => {
    selectedDrawingIds.value = [];
    selectedShapeId.value = null;
    if (transformerNode.value) {
        transformerNode.value.getNode().nodes([]);
    }
};

const handleDrawingSelectionStart = (e: any): void => {
    const pos = e.target.getStage().getRelativePointerPosition();
    drawingSelectionBox.value = { x: pos.x, y: pos.y, width: 0, height: 0, visible: true };
};

const handleDrawingSelectionMove = (e: any): void => {
    if (!drawingSelectionBox.value.visible) return;
    const pos = e.target.getStage().getRelativePointerPosition();
    drawingSelectionBox.value.width = pos.x - drawingSelectionBox.value.x;
    drawingSelectionBox.value.height = pos.y - drawingSelectionBox.value.y;
};

const handleDrawingSelectionEnd = (): void => {
    if (!drawingSelectionBox.value.visible) return;

    const box = drawingSelectionBox.value;
    const x1 = Math.min(box.x, box.x + box.width);
    const x2 = Math.max(box.x, box.x + box.width);
    const y1 = Math.min(box.y, box.y + box.height);
    const y2 = Math.max(box.y, box.y + box.height);

    const activeLayer = activeLayerId.value;
    const layerDrawings = activeLayer === 'tokens'
        ? drawings.value
        : drawings.value.filter(d => d.layer === activeLayer);

    const selected = layerDrawings.filter(d => {
        if (d.type === 'rect' || d.type === 'image') {
            const dx = (d.scaleX ?? 1) * d.width;
            const dy = (d.scaleY ?? 1) * d.height;
            return d.x < x2 && d.x + dx > x1 && d.y < y2 && d.y + dy > y1;
        }
        if (d.type === 'pen') {
            const pts = d.points ?? [];
            return pts.some((_, i) =>
                i % 2 === 0 &&
                pts[i] >= x1 && pts[i] <= x2 &&
                pts[i + 1] >= y1 && pts[i + 1] <= y2
            );
        }
        return false;
    });

    selectedDrawingIds.value = selected.map(d => d.id);
    drawingSelectionBox.value.visible = false;
};

const bulkDeleteSelectedDrawings = async (): Promise<void> => {
    if (selectedDrawingIds.value.length === 0) return;
    const idsToDelete = [...selectedDrawingIds.value];
    clearDrawingSelection();
    drawings.value = drawings.value.filter(d => !idsToDelete.includes(d.id));
    await Promise.all(
        idsToDelete.map(id =>
            axios.delete(`/session/drawings/${id}`).catch((error: unknown) => {
                console.error('Błąd usuwania rysunku', error);
            })
        )
    );
};

const toggleFog = async (): Promise<void> => {
    if (fogEnabled.value) {
        const metaIds = drawings.value.filter(d => d.type === 'fog_meta').map(d => d.id);
        drawings.value = drawings.value.filter(d => d.type !== 'fog_meta');
        await Promise.all(metaIds.map(id =>
            axios.delete(`/session/drawings/${id}`).catch((e: unknown) => console.error('Błąd wyłączania mgły', e))
        ));
    } else {
        const tempId = Date.now();
        drawings.value.push({
            id: tempId, type: 'fog_meta', layer: 'map' as DrawingLayerId,
            x: 0, y: 0, width: 0, height: 0, points: [], strokeWidth: 0, scaleX: 1, scaleY: 1, rotation: 0,
        });
        try {
            const { data } = await axios.post<{ id: number }>('/session/drawings/store', {
                type: 'fog_meta', layer: 'map',
                data: { x: 0, y: 0, width: 0, height: 0, points: [], strokeWidth: 0, scaleX: 1, scaleY: 1, rotation: 0 },
            });
            const meta = drawings.value.find(d => d.id === tempId);
            if (meta) meta.id = data.id;
        } catch (e: unknown) {
            drawings.value = drawings.value.filter(d => d.id !== tempId);
            console.error('Błąd włączania mgły', e);
        }
    }
};

const clearFogShapes = async (): Promise<void> => {
    const fogIds = drawings.value.filter(d => d.type === 'fog' || d.type === 'fog_hide').map(d => d.id);
    drawings.value = drawings.value.filter(d => d.type !== 'fog' && d.type !== 'fog_hide');
    await Promise.all(fogIds.map(id =>
        axios.delete(`/session/drawings/${id}`).catch((e: unknown) => console.error('Błąd czyszczenia mgły', e))
    ));
};

// Podczas przeciągania obrazu — podpis i zaznaczone rysunki podążają za kursorem
const handleImageDragMove = (e: any, draw: DrawingData): void => {
    const newX = e.target.x();
    const newY = e.target.y();
    const dx = newX - draw.x;
    const dy = newY - draw.y;

    // Przesuń wszystkie pozostałe zaznaczone rysunki o ten sam delta
    if (selectedDrawingIds.value.length > 1) {
        drawings.value.forEach(d => {
            if (selectedDrawingIds.value.includes(d.id) && d.id !== draw.id) {
                d.x += dx;
                d.y += dy;
            }
        });
    }

    draw.x = newX;
    draw.y = newY;
};

// Po zakończeniu przeciągania — zapisz pozycje wszystkich przesuniętych rysunków
const handleImageDragEnd = async (e: any, draw: DrawingData): Promise<void> => {
    draw.x = e.target.x();
    draw.y = e.target.y();

    if (selectedDrawingIds.value.length > 1) {
        const toSave = drawings.value.filter(d => selectedDrawingIds.value.includes(d.id));
        await Promise.all(
            toSave.map(d =>
                axios.patch(`/session/drawings/${d.id}`, { id: d.id, data: d })
                    .catch((error: unknown) => console.error('Błąd zapisu pozycji rysunku', error))
            )
        );
    } else {
        await handleTransformEnd(e, draw);
    }
};

// Po zakończeniu skalowania/przesuwania
const handleTransformEnd = async (e: any, draw: any) => {
    const node = e.target;
    draw.x = node.x();
    draw.y = node.y();
    draw.scaleX = node.scaleX();
    draw.scaleY = node.scaleY();
    draw.rotation = node.rotation();

    const updatedData = {
        id: draw.id,
        data: draw,
    };

    await axios.patch(`/session/drawings/${node.id()}`, updatedData);
};

const stageScale = ref(1);
const stagePos  = ref({ x: 0, y: 0 });
const stageSize = ref({ width: window.innerWidth, height: window.innerHeight });
const isPanning = ref(false);
const lastPanPos = ref({ x: 0, y: 0 });
const gmFogVisible = ref(false);
const fogMode = ref<'reveal' | 'hide'>('reveal');

const stageConfig = computed(() => ({
    width:  stageSize.value.width,
    height: stageSize.value.height,
    scaleX: stageScale.value,
    scaleY: stageScale.value,
    x: stagePos.value.x,
    y: stagePos.value.y,
}));

const loadImage = (token: Token) => {
    if (!token.image) return;

    const img = new window.Image();
    img.src = token.image_url;
    img.onload = () => {
        loadedImages.value[token.id] = img;
    };
};

const updateSize = () => {
    stageSize.value.width  = window.innerWidth;
    stageSize.value.height = window.innerHeight;
};


// Aktualizacja pozycji po przeciągnięciu
const updateTokenPosition = async (event, token) => {
    const { x, y } = event.target.attrs;

    token.x = x;
    token.y = y;

    try {
        await axios.patch(`/session/tokens/${token.id}/move`, { x, y });
        console.log(`Token ${token.name} zapisany na pozycji: ${x}, ${y}`);
    } catch (error) {
        console.error("Błąd zapisu:", error);
    }
};
const selectedIds = ref<number[]>([]);
const mouseDownPos = ref<{ x: number; y: number } | null>(null);
const mouseDownTarget = ref<any>(null);
const selectionBox = ref({
    x: 0,
    y: 0,
    width: 0,
    height: 0,
    visible: false
});

// Funkcja pomocnicza do sprawdzania, czy token jest wewnątrz prostokąta
const isInside = (token: Token, box: any) => {
    // Obliczamy granice prostokąta (obsługuje przeciąganie w każdą stronę)
    const x1 = Math.min(box.x, box.x + box.width);
    const x2 = Math.max(box.x, box.x + box.width);
    const y1 = Math.min(box.y, box.y + box.height);
    const y2 = Math.max(box.y, box.y + box.height);

    return token.x >= x1 && token.x <= x2 && token.y >= y1 && token.y <= y2;
};

const handleSelectionStart = (e: any) => {
    // Jeśli klikniemy w tło (pusty obszar)
    if (e.target === e.target.getStage()) {
        // Resetujemy zaznaczenie rysunku
        selectedShapeId.value = null;
        if (transformerNode.value) {
            transformerNode.value.getNode().nodes([]);
        }

        // Twoja stara logika zaznaczania prostokątem (tylko dla narzędzia select)
        if (activeTool.value === 'select') {
            const pos = e.target.getStage().getRelativePointerPosition();
            selectionBox.value = { x: pos.x, y: pos.y, width: 0, height: 0, visible: true };
            selectedIds.value = [];
        }
    }
};
const handleSelectionMove = (e: any) => {
    if (!selectionBox.value.visible) return;

    const pos = e.target.getStage().getRelativePointerPosition();
    selectionBox.value.width = pos.x - selectionBox.value.x;
    selectionBox.value.height = pos.y - selectionBox.value.y;
};

const handleSelectionEnd = () => {
    if (!selectionBox.value.visible) return;

    // Zaznaczamy tylko tokeny, które należą do gracza (zgodnie z Twoją logiką props.heroId)
    const newlySelected = tokens.value
        .filter(t => isInside(t, selectionBox.value) && t.hero_id === props.heroId)
        .map(t => t.id);

    selectedIds.value = newlySelected;
    selectionBox.value.visible = false;
};
const getTokenFromEvent = (e: any): Token | undefined => {
    let node = e.target;
    while (node && node.getType() !== 'Stage') {
        if (node.getType() === 'Group' && node.id()) {
            return tokens.value.find(t => t.id === parseInt(node.id()));
        }
        node = node.getParent();
    }
    return undefined;
};

const handleTokenLayerDragMove = (e: any): void => {
    const draggedToken = getTokenFromEvent(e);
    if (!draggedToken) return;

    if (!selectedIds.value.includes(draggedToken.id)) {
        selectedIds.value = [draggedToken.id];
        return;
    }

    const { x, y } = e.target.attrs;
    const dx = x - draggedToken.x;
    const dy = y - draggedToken.y;

    tokens.value.forEach(t => {
        if (selectedIds.value.includes(t.id) && t.id !== draggedToken.id) {
            t.x += dx;
            t.y += dy;
        }
    });

    draggedToken.x = x;
    draggedToken.y = y;
};

const handleGroupDragEnd = async () => {
    const movedTokens = tokens.value
        .filter(t => selectedIds.value.includes(t.id))
        .map(t => ({
            id: t.id,
            name: t.name,
            hero_id: t.hero_id,
            image: t.image,
            x: t.x,
            y: t.y
        }));

    if (movedTokens.length === 0) return;

    try {
        await axios.patch('/session/tokens/bulk-move', {
            tokens: movedTokens
        });

        console.log(`Zaktualizowano grupę: ${movedTokens.length} tokenów.`);
    } catch (error) {
        console.error("Błąd zapisu grupowego:", error);
    }
};

const handleWheel = (e: any): void => {
    e.evt.preventDefault();
    const stage = stageRef.value?.getNode();
    if (!stage) return;
    const pointer = stage.getPointerPosition();
    if (!pointer) return;

    const scaleBy  = 1.12;
    const oldScale = stageScale.value;
    const newScale = e.evt.deltaY < 0 ? oldScale * scaleBy : oldScale / scaleBy;
    const clamped  = Math.max(0.1, Math.min(8, newScale));

    // Zoom wycentrowany na kursorze
    stagePos.value = {
        x: pointer.x - (pointer.x - stagePos.value.x) * (clamped / oldScale),
        y: pointer.y - (pointer.y - stagePos.value.y) * (clamped / oldScale),
    };
    stageScale.value = clamped;
};

const handleStageMouseDown = (e: any) => {
    // Zapamiętujemy pozycję i cel mousedown, żeby wykryć kliknięcie w mouseup
    mouseDownPos.value = { x: e.evt.clientX, y: e.evt.clientY };
    mouseDownTarget.value = e.target;

    // Środkowy przycisk myszy → pan
    if (e.evt.button === 1) {
        isPanning.value  = true;
        lastPanPos.value = { x: e.evt.clientX, y: e.evt.clientY };
        e.evt.preventDefault();
        return;
    }
    if (activeTool.value === 'eraser') {
        return;
    }
    if (activeTool.value === 'select-draw') {
        // Klik w puste miejsce — zacznij box-select i wyczyść selekcję
        if (e.target === e.target.getStage()) {
            clearDrawingSelection();
            handleDrawingSelectionStart(e);
        }
        return;
    }
    if (activeTool.value === 'select') {
        handleSelectionStart(e);
        return;
    }

    if (activeTool.value === 'fog') {
        if (!fogEnabled.value) { toggleFog(); }
        const pos = e.target.getStage().getRelativePointerPosition();
        isDrawing.value = true;
        drawings.value.push({
            id: Date.now(),
            type: fogMode.value === 'reveal' ? 'fog' : 'fog_hide',
            layer: 'map' as DrawingLayerId,
            x: pos.x, y: pos.y, width: 0, height: 0,
            points: [], strokeWidth: 0, scaleX: 1, scaleY: 1, rotation: 0,
        });
        return;
    }

    const pos = e.target.getStage().getRelativePointerPosition();

    const drawingLayer: DrawingLayerId = activeLayerId.value === 'tokens' ? 'map' : activeLayerId.value;

    if (activeTool.value === 'pen') {
        isDrawing.value = true;
        drawings.value.push({
            id: Date.now(),
            type: 'pen',
            layer: drawingLayer,
            points: [pos.x, pos.y],
            x: 0,
            y: 0,
            width: 0,
            height: 0,
            stroke: '#ff0000',
            strokeWidth: 3,
            tension: 0.5,
            lineCap: 'round',
            lineJoin: 'round',
            scaleX: 1,
            scaleY: 1,
            rotation: 0,
        } as DrawingData);
    } else if (activeTool.value === 'rect') {
        isDrawing.value = true;
        drawings.value.push({
            id: Date.now(),
            type: 'rect',
            layer: drawingLayer,
            x: pos.x,
            y: pos.y,
            width: 0,
            height: 0,
            points: [],
            stroke: '#ff0000',
            strokeWidth: 2,
            scaleX: 1,
            scaleY: 1,
            rotation: 0,
        } as DrawingData);
    } else if (activeTool.value === 'ping') {
        const newPing = {
            id: Date.now(),
            x: pos.x,
            y: pos.y,
            color: pingColor.value
        };

        createPing(newPing);
        axios.post('/session/ping', newPing);
        return;
    }
};

const createPing = (pingData: any) => {
    pings.value.push(pingData);

    setTimeout(() => {
        pings.value = pings.value.filter(p => p.id !== pingData.id);
    }, 3000);
};
const handleStageMouseMove = (e: any) => {
    if (isPanning.value) {
        const dx = e.evt.clientX - lastPanPos.value.x;
        const dy = e.evt.clientY - lastPanPos.value.y;
        stagePos.value   = { x: stagePos.value.x + dx, y: stagePos.value.y + dy };
        lastPanPos.value = { x: e.evt.clientX, y: e.evt.clientY };
        return;
    }
    if (activeTool.value === 'select-draw') {
        handleDrawingSelectionMove(e);
        return;
    }
    if (!isDrawing.value || activeTool.value === 'select') {
        handleSelectionMove(e);
        return;
    }

    const pos = e.target.getStage().getRelativePointerPosition();
    const lastShape = drawings.value[drawings.value.length - 1];

    if (activeTool.value === 'pen') {
        lastShape.points = lastShape.points.concat([pos.x, pos.y]);
    } else if (activeTool.value === 'rect' || activeTool.value === 'fog') {
        lastShape.width = pos.x - lastShape.x;
        lastShape.height = pos.y - lastShape.y;
    }
};

const sendMessage = async () => {
    if (newMessage.value.trim() === '') return;

    try {
        const { data } = await axios.post('/session/chat/send', {
            text: newMessage.value
        });

        newMessage.value = '';
        scrollToBottom();
    } catch (error) {
        console.error("Błąd wysyłania wiadomości");
    }
};

const playDiceSound = () => {
    const AudioCtx = window.AudioContext || (window as any).webkitAudioContext;
    if (!AudioCtx) return;

    const ctx = new AudioCtx();
    const sampleRate = ctx.sampleRate;

    // Kilka "klaknięć" kostką o stół z malejącą głośnością
    const clacks = [0, 0.09, 0.17, 0.27, 0.36];
    const totalDuration = 0.55;
    const bufferSize = Math.floor(sampleRate * totalDuration);
    const buffer = ctx.createBuffer(1, bufferSize, sampleRate);
    const data = buffer.getChannelData(0);

    clacks.forEach((clackTime, idx) => {
        const start = Math.floor(clackTime * sampleRate);
        const clackLen = Math.floor(0.045 * sampleRate);
        const volume = 1 - idx * 0.15;
        for (let i = 0; i < clackLen && start + i < bufferSize; i++) {
            const env = Math.exp(-i / (clackLen * 0.25));
            data[start + i] += (Math.random() * 2 - 1) * env * volume;
        }
    });

    const source = ctx.createBufferSource();
    source.buffer = buffer;

    const filter = ctx.createBiquadFilter();
    filter.type = 'bandpass';
    filter.frequency.value = 1800;
    filter.Q.value = 0.8;

    const gain = ctx.createGain();
    gain.gain.value = 0.65;

    source.connect(filter);
    filter.connect(gain);
    gain.connect(ctx.destination);
    source.start();
    source.onended = () => ctx.close();
};

const parseSkillTest = (text: string): SkillTestResult | null => {
    try {
        return JSON.parse(text) as SkillTestResult;
    } catch (e) {
        console.error('Failed to parse skill test message', e);
        return null;
    }
};

const toggleSkillPicker = async () => {
    showSkillPicker.value = !showSkillPicker.value;
    if (showSkillPicker.value && skills.value.length === 0) {
        isLoadingSkills.value = true;
        try {
            const { data } = await axios.get<SkillOption[]>('/session/chat/skills');
            skills.value = data;
        } catch (e) {
            console.error('Błąd pobierania umiejętności', e);
        } finally {
            isLoadingSkills.value = false;
        }
    }
};

const rollSkill = async (skillId: number) => {
    if (isRollingSkill.value) return;
    isRollingSkill.value = true;
    showSkillPicker.value = false;
    playDiceSound();
    try {
        await axios.post('/session/chat/roll-skill', {
            skill_id: skillId,
            modifier: skillModifier.value,
            half: skillHalf.value,
        });
        scrollToBottom();
    } catch (e) {
        console.error('Błąd testu umiejętności', e);
    } finally {
        skillModifier.value = 0;
        skillHalf.value = false;
        isRollingSkill.value = false;
    }
};

const parseRoll = (text: string) => {
    const match = text.match(/Zr \((\d+)\) \+ k10 \[(\d+)\] = (\d+)/);
    return match
        ? { zr: match[1], dice: match[2], total: match[3] }
        : { zr: '?', dice: '?', total: '?' };
};

const rollInitiative = async () => {
    if (isRolling.value) return;
    isRolling.value = true;
    playDiceSound();
    try {
        const { data } = await axios.post('/session/chat/roll-initiative');
        scrollToBottom();
    } catch (error) {
        console.error('Błąd rzutu na inicjatywę');
    } finally {
        isRolling.value = false;
    }
};

const scrollToBottom = () => {
    setTimeout(() => {
        if (messageContainer.value) {
            messageContainer.value.scrollTop = messageContainer.value.scrollHeight;
        }
    }, 50);
};

const handleStageMouseUp = async (e: any) => {
    if (isPanning.value) {
        isPanning.value = false;
        mouseDownPos.value = null;
        return;
    }

    // Wykrycie kliknięcia: mousedown i mouseup w tym samym miejscu (ruch < 5px)
    // Konva blokuje zdarzenie 'click' na węzłach draggable, dlatego wykrywamy sami
    if (
        props.hasDrawingPermission &&
        mouseDownPos.value &&
        e?.evt &&
        Math.abs(e.evt.clientX - mouseDownPos.value.x) < 5 &&
        Math.abs(e.evt.clientY - mouseDownPos.value.y) < 5 &&
        mouseDownTarget.value &&
        mouseDownTarget.value !== mouseDownTarget.value.getStage()
    ) {
        // Szukamy tokenu NPC w hierarchii Konvy
        let node = mouseDownTarget.value;
        let foundToken: Token | undefined;
        while (node && node.getType && node.getType() !== 'Stage') {
            if (node.getType() === 'Group' && node.id()) {
                foundToken = tokens.value.find(t => t.id === parseInt(node.id()));
                break;
            }
            node = node.getParent?.();
        }
        if (foundToken && foundToken.hero_id == null) {
            selectedNpcToken.value = foundToken;
        }
    }
    mouseDownPos.value = null;
    mouseDownTarget.value = null;

    if (activeTool.value === 'select-draw') {
        handleDrawingSelectionEnd();
        return;
    }
    if (isDrawing.value) {
        isDrawing.value = false;
        const lastShape = drawings.value[drawings.value.length - 1];
        const tempId = lastShape.id;
        // Pomijamy zbyt małe kształty (klik bez przeciągnięcia)
        if ((lastShape.type === 'fog' || lastShape.type === 'fog_hide') && Math.abs(lastShape.width) < 5 && Math.abs(lastShape.height) < 5) {
            drawings.value = drawings.value.filter(d => d.id !== tempId);
            return;
        }
        try {
            const { id: _id, layer: _layer, type: _type, ...shapeData } = lastShape;
            const { data } = await axios.post<{ id: number }>('/session/drawings/store', {
                type: lastShape.type,
                layer: lastShape.layer,
                data: shapeData,
            });
            lastShape.id = data.id;
        } catch (error: unknown) {
            console.error('Błąd zapisu rysunku', error);
            drawings.value = drawings.value.filter(d => d.id !== tempId);
        }
    }
    handleSelectionEnd();
};

const formatDate = (isoString: string) => {
    const date = new Date(isoString);

    return new Intl.DateTimeFormat('pl-PL', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    }).format(date);
};

const handleKeyDown = (e: KeyboardEvent): void => {
    if (e.key === 'Delete' && selectedDrawingIds.value.length > 0) {
        bulkDeleteSelectedDrawings();
    }
};

const handleWindowMouseUp = (e: MouseEvent): void => {
    if (e.button === 1) isPanning.value = false;
};

onMounted(() => {
    fetchDrawings();
    fetchTokens();
    fetchMessages();
    fetchAssets();
    window.addEventListener('resize', updateSize);
    window.addEventListener('keydown', handleKeyDown);
    window.addEventListener('mouseup', handleWindowMouseUp);
});
onUnmounted(() => {
    window.removeEventListener('resize', updateSize);
    window.removeEventListener('keydown', handleKeyDown);
    window.removeEventListener('mouseup', handleWindowMouseUp);
    window.Echo.leaveChannel('token-move');
    window.Echo.leaveChannel('session-chat');
});
</script>

<style scoped>
.game-container {
    position: fixed; /* Wyrywa element z normalnego układu */
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: #1a1a1a; /* Jeszcze ciemniejszy, bitewny kolor */
    z-index: 9999; /* Musi być wyżej niż Twój header "Book of Grudges" */
    overflow: hidden;
}

/* Dodatek: styl dla przycisku wyjścia, jeśli będziesz go chciał */
.exit-button {
    position: absolute;
    top: 20px;
    right: 20px;
    z-index: 10000;
    padding: 10px;
    background: #444;
    color: white;
    cursor: pointer;
}
.toolbar {
    position: fixed;
    top: 20px;
    left: 20px;
    z-index: 10001;
    display: flex;
    gap: 10px;
    background: rgba(0,0,0,0.7);
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #d4af37;
}
button { background: #333; color: white; border: 1px solid #555; padding: 5px 10px; cursor: pointer; }
button.active { background: #d4af37; color: black; }

/* .chat-container — zastąpiony przez FloatingPanel */

/* Link do karty bohatera w nagłówku czatu */
.hero-sheet-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    color: #7a5035;
    text-decoration: none;
    padding: 2px 4px;
    border-radius: 3px;
    line-height: 1;
    transition: color 0.15s;
}
.hero-sheet-link:hover { color: #d4af37; }

/* Wewnętrzny layout czatu */

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 10px;
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.message { font-size: 0.95rem; line-height: 1.2; border-bottom: 1px solid #333; padding-bottom: 2px; }
.msg-author { font-weight: bold; margin-right: 5px; }
.msg-content { color: #ccc; word-break: break-word; display: block; }
.msg-time { font-size: 0.7rem; color: #666; float: right; }

/* Roll card */
.message-roll-card {
    border: 1px solid #d4af37;
    border-radius: 6px;
    background: linear-gradient(135deg, #1a1500 0%, #0f0f0f 100%);
    padding: 8px 10px;
    margin: 4px 0;
    box-shadow: 0 0 12px rgba(212, 175, 55, 0.15), inset 0 0 20px rgba(0,0,0,0.4);
}

.roll-card-header {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 4px;
}

.roll-card-icon { font-size: 1rem; }

.roll-card-type {
    font-size: 0.65rem;
    font-weight: 800;
    letter-spacing: 2px;
    color: #d4af37;
    text-transform: uppercase;
    flex: 1;
}

.roll-card-time {
    font-size: 0.65rem;
    color: #555;
}

.roll-card-author {
    font-size: 0.8rem;
    color: #aaa;
    margin-bottom: 8px;
    font-style: italic;
}

.roll-card-breakdown {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.roll-op {
    color: #888;
    font-size: 1.1rem;
    font-weight: bold;
}

.roll-die {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: #1e1e1e;
    border: 1px solid #444;
    border-radius: 5px;
    padding: 4px 10px;
    min-width: 48px;
}

.roll-die-d10 {
    border-color: #d4af37;
    background: #1a1500;
}

.roll-die-total {
    border: 2px solid #d4af37;
    background: #d4af37;
    box-shadow: 0 0 10px rgba(212, 175, 55, 0.5);
    min-width: 54px;
}

.roll-die-value {
    font-size: 1.3rem;
    font-weight: 800;
    color: #fff;
    line-height: 1;
}

.roll-die-total .roll-die-value {
    color: #1a1a1a;
    font-size: 1.5rem;
}

.roll-die-label {
    font-size: 0.55rem;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 2px;
}

.roll-die-total .roll-die-label { color: #5a4a00; }

/* Dice overlay */
.dice-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.82);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    border-radius: inherit;
    z-index: 20;
}

.dice-overlay-die {
    font-size: 3rem;
    animation: diceSpin 0.6s ease-in-out infinite alternate;
    filter: drop-shadow(0 0 12px rgba(212, 175, 55, 0.8));
}

.dice-overlay-label {
    color: #d4af37;
    font-size: 0.85rem;
    letter-spacing: 1px;
    text-transform: uppercase;
}

@keyframes diceSpin {
    from { transform: rotate(-20deg) scale(0.9); }
    to   { transform: rotate(20deg)  scale(1.1); }
}

.chat-input-area {
    padding: 10px;
    display: flex;
    gap: 5px;
    background: #111;
}

.chat-input-area input {
    flex: 1;
    background: #222;
    border: 1px solid #444;
    color: white;
    padding: 5px;
    border-radius: 4px;
}

.chat-input-area button {
    background: #d4af37;
    border: none;
    color: black;
    padding: 0 10px;
    cursor: pointer;
    border-radius: 4px;
}

.chat-actions {
    padding: 5px 10px 8px;
    background: #111;
    display: flex;
    gap: 5px;
}

.roll-btn {
    background: #2a2a1a;
    border: 1px solid #d4af37;
    color: #d4af37;
    padding: 4px 10px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.85rem;
    transition: background 0.15s;
}

.roll-btn:hover:not(:disabled) { background: #3a3a1a; }
.roll-btn:disabled { opacity: 0.5; cursor: not-allowed; }
.roll-btn.active { background: #3a3a00; border-color: #ffdf00; }

/* Skill picker */
.skill-picker {
    background: #0d0d0d;
    border-top: 1px solid #333;
    display: flex;
    flex-direction: column;
    max-height: 260px;
}

.skill-picker-modifiers {
    display: flex;
    gap: 3px;
    padding: 7px 7px 0;
    flex-wrap: wrap;
}

.mod-btn {
    flex: 1;
    min-width: 34px;
    padding: 3px 2px;
    border-radius: 3px;
    border: 1px solid #2a2a2a;
    background: #141414;
    color: #777;
    font-size: 0.7rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.1s;
    text-align: center;
}

.mod-btn:hover { border-color: #555; color: #ccc; background: #1e1e1e; }
.mod-neg { color: #c0392b; }
.mod-pos { color: #27ae60; }
.mod-zero { color: #777; }
.mod-active { border-color: #d4af37 !important; background: #1a1500 !important; color: #d4af37 !important; box-shadow: 0 0 6px rgba(212,175,55,0.3); }

.skill-picker-options {
    padding: 5px 7px 3px;
    display: flex;
    gap: 5px;
}

.half-btn {
    background: #141414;
    border: 1px solid #2a2a2a;
    color: #777;
    padding: 4px 10px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.78rem;
    transition: all 0.12s;
    width: 100%;
}

.half-btn:hover { border-color: #555; color: #ccc; }
.half-active { border-color: #7b68ee !important; color: #9d91f0 !important; background: #0e0d1a !important; }

.skill-picker-search {
    margin: 8px;
    background: #1a1a1a;
    border: 1px solid #444;
    color: white;
    padding: 5px 8px;
    border-radius: 4px;
    font-size: 0.85rem;
    outline: none;
}

.skill-picker-search:focus { border-color: #d4af37; }

.skill-picker-list {
    overflow-y: auto;
    flex: 1;
    padding: 0 6px 6px;
}

.skill-group-label {
    font-size: 0.6rem;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: #555;
    padding: 6px 4px 2px;
}

.skill-item {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #141414;
    border: 1px solid #2a2a2a;
    color: #aaa;
    padding: 5px 8px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.82rem;
    margin-bottom: 2px;
    text-align: left;
    transition: border-color 0.12s, background 0.12s;
}

.skill-item:hover {
    background: #1e1e1e;
    border-color: #555;
    color: #ddd;
}

.skill-item-purchased {
    border-color: rgba(212, 175, 55, 0.4);
    color: #e8d68a;
    background: #16130a;
}

.skill-item-purchased:hover {
    background: #201c0e;
    border-color: #d4af37;
}

.skill-item-name { flex: 1; }

.skill-item-char {
    font-size: 0.7rem;
    color: #666;
    margin-left: 6px;
    white-space: nowrap;
    font-family: monospace;
}

.skill-item-purchased .skill-item-char { color: #a08030; }

.skill-picker-info {
    color: #555;
    font-size: 0.8rem;
    text-align: center;
    padding: 12px;
}

/* Skill test card */
.message-skill-card {
    border-radius: 6px;
    padding: 8px 10px;
    margin: 4px 0;
    border: 1px solid #333;
    background: #0f0f0f;
}

.skill-passed { border-color: #2e7d32; background: linear-gradient(135deg, #071209 0%, #0f0f0f 100%); }
.skill-failed  { border-color: #7f1d1d; background: linear-gradient(135deg, #120707 0%, #0f0f0f 100%); }

.skill-card-header {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-bottom: 3px;
}

.skill-card-icon { font-size: 0.9rem; }

.skill-card-type {
    font-size: 0.6rem;
    font-weight: 800;
    letter-spacing: 2px;
    color: #888;
    text-transform: uppercase;
    flex: 1;
}

.skill-card-time { font-size: 0.65rem; color: #555; }

.skill-card-author { font-size: 0.78rem; color: #888; font-style: italic; margin-bottom: 5px; }

.skill-card-name {
    font-size: 0.9rem;
    font-weight: 700;
    color: #ddd;
    margin-bottom: 7px;
}

.skill-card-char { font-size: 0.75rem; color: #666; font-weight: normal; }

.skill-card-effective {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.78rem;
    margin-bottom: 6px;
    color: #888;
}

.eff-base { color: #aaa; font-weight: 700; }
.eff-op { color: #9d91f0; font-weight: 700; }
.eff-sep { color: #555; }
.eff-result { color: #d4af37; font-weight: 800; font-size: 0.88rem; }

.skill-card-breakdown {
    display: flex;
    align-items: center;
    gap: 8px;
}

.skill-stat {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: #1a1a1a;
    border: 1px solid #333;
    border-radius: 5px;
    padding: 4px 10px;
    min-width: 44px;
}

.skill-stat-value {
    font-size: 1.2rem;
    font-weight: 800;
    color: #fff;
    line-height: 1;
}

.skill-stat-label {
    font-size: 0.55rem;
    color: #555;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 1px;
}

.skill-vs { color: #555; font-size: 0.8rem; font-weight: bold; }

.skill-verdict {
    flex: 1;
    text-align: right;
    font-size: 0.9rem;
    font-weight: 800;
    letter-spacing: 1px;
}

.skill-verdict-pass { color: #4caf50; text-shadow: 0 0 8px rgba(76, 175, 80, 0.4); }
.skill-verdict-fail { color: #f44336; text-shadow: 0 0 8px rgba(244, 67, 54, 0.4); }

/* ── Layers panel — zastąpiony przez FloatingPanel ── */
/* Treść wewnątrz panelu warstw */
.layer-list-body {
    display: flex;
    flex-direction: column;
    gap: 4px;
    padding: 6px;
    overflow-y: auto;
    height: 100%;
}

.layer-row {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 5px 6px;
    border-radius: 5px;
    cursor: pointer;
    border: 1px solid transparent;
    transition: background 0.12s, border-color 0.12s;
}

.layer-row:hover { background: rgba(255,255,255,0.05); border-color: #444; }

.layer-active {
    background: rgba(212, 175, 55, 0.1) !important;
    border-color: #d4af37 !important;
}

.layer-color-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}

.layer-name {
    flex: 1;
    font-size: 0.78rem;
    color: #ccc;
    white-space: nowrap;
}

.layer-active .layer-name { color: #f5d97b; font-weight: 700; }

.layer-actions {
    display: flex;
    gap: 2px;
}

.layer-icon-btn {
    background: none;
    border: none;
    padding: 1px 3px;
    font-size: 0.75rem;
    cursor: pointer;
    color: #888;
    border-radius: 3px;
    line-height: 1;
    transition: background 0.1s, color 0.1s;
}

.layer-icon-btn:hover { background: rgba(255,255,255,0.08); color: #ddd; }

.layer-move-btn {
    background: none;
    border: 1px solid #555;
    padding: 1px 4px;
    font-size: 0.7rem;
    cursor: pointer;
    color: #aaa;
    border-radius: 3px;
    line-height: 1;
    transition: border-color 0.1s, color 0.1s, background 0.1s;
}

.layer-move-btn:hover {
    border-color: #d4af37;
    color: #d4af37;
    background: rgba(212, 175, 55, 0.1);
}

.layers-divider {
    height: 1px;
    background: #333;
    margin: 2px 4px;
}

.stage-wrapper {
    position: absolute;
    inset: 0;
}

.stage-wrapper.cursor-grab canvas {
    cursor: grabbing !important;
}

.zoom-control {
    display: flex;
    align-items: center;
    gap: 2px;
    background: rgba(0,0,0,0.4);
    border: 1px solid #555;
    border-radius: 4px;
    padding: 0 4px;
}

.zoom-btn {
    background: none;
    border: none;
    color: #ccc;
    font-size: 1rem;
    line-height: 1;
    padding: 2px 6px;
    cursor: pointer;
}

.zoom-btn:hover { color: #d4af37; }

.zoom-label {
    font-size: 0.72rem;
    color: #ccc;
    min-width: 38px;
    text-align: center;
    cursor: pointer;
    font-family: monospace;
}

.zoom-label:hover { color: #d4af37; }

/* ── Schowek — zastąpiony przez FloatingPanel ── */
.stash-body {
    padding: 10px;
    overflow-y: auto;
    height: 100%;
}

.stash-upload {
    display: flex;
    gap: 8px;
    margin-bottom: 10px;
    align-items: center;
}

.stash-type-select {
    background: #1a1a1a;
    border: 1px solid #444;
    color: #ccc;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.82rem;
    cursor: pointer;
}

.stash-upload-btn {
    background: #1a1500;
    border: 1px solid #d4af37;
    color: #d4af37;
    padding: 5px 14px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.82rem;
    white-space: nowrap;
    transition: background 0.12s;
}

.stash-upload-btn:hover { background: #2a2000; }
.stash-uploading { opacity: 0.6; cursor: not-allowed; }

.stash-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    max-height: 220px;
    overflow-y: auto;
}

.stash-item {
    position: relative;
    width: 80px;
    border: 1px solid #333;
    border-radius: 6px;
    background: #111;
    cursor: grab;
    overflow: hidden;
    transition: border-color 0.12s;
}

.stash-item:hover { border-color: #d4af37; }
.stash-item:active { cursor: grabbing; }

.stash-thumb {
    width: 100%;
    height: 60px;
    object-fit: cover;
    display: block;
}

.stash-item-footer {
    display: flex;
    align-items: center;
    padding: 2px 4px;
    gap: 2px;
}

.stash-item-name {
    flex: 1;
    font-size: 0.6rem;
    color: #aaa;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.stash-item-delete {
    background: none;
    border: none;
    color: #666;
    font-size: 0.65rem;
    cursor: pointer;
    padding: 1px 3px;
    border-radius: 2px;
    line-height: 1;
    flex-shrink: 0;
}

.stash-item-delete:hover { color: #e74c3c; background: rgba(231,76,60,0.1); }

.stash-type-badge {
    position: absolute;
    top: 3px;
    right: 3px;
    background: rgba(0,0,0,0.7);
    color: #d4af37;
    font-size: 0.5rem;
    padding: 1px 4px;
    border-radius: 3px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stash-empty {
    color: #444;
    font-size: 0.8rem;
    padding: 12px;
    width: 100%;
    text-align: center;
}

.delete-selected-btn {
    background: #3a0a0a;
    border: 1px solid #c0392b;
    color: #e74c3c;
    padding: 5px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.82rem;
    font-weight: 600;
    transition: background 0.15s, color 0.15s;
    white-space: nowrap;
}

.delete-selected-btn:hover {
    background: #5a1010;
    color: #ff6b6b;
    border-color: #e74c3c;
}

/* ── Schowek tokenów NPC — zastąpiony przez FloatingPanel ── */
.npc-stash-body {
    display: flex;
    flex-direction: column;
    gap: 0;
    padding: 6px;
    height: 100%;
    overflow: hidden;
}

.npc-search {
    width: 100%;
    background: #111;
    border: 1px solid #3a3a3a;
    border-radius: 4px;
    color: #e4d8b4;
    font-size: 0.75rem;
    padding: 4px 8px;
    margin-bottom: 6px;
    outline: none;
    box-sizing: border-box;
    transition: border-color 0.15s;
}

.npc-search:focus {
    border-color: #d4af37;
}

.npc-search::placeholder {
    color: #555;
}

.npc-stash-scroll {
    flex: 1;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 4px;
    scrollbar-width: thin;
    scrollbar-color: #3a3a3a transparent;
}

.npc-stash-scroll::-webkit-scrollbar { width: 4px; }
.npc-stash-scroll::-webkit-scrollbar-track { background: transparent; }
.npc-stash-scroll::-webkit-scrollbar-thumb { background: #3a3a3a; border-radius: 2px; }

.npc-token-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 5px 7px;
    border-radius: 5px;
    background: rgba(255,255,255,0.04);
    border: 1px solid transparent;
    transition: background 0.15s, border-color 0.15s;
    cursor: grab;
}

.npc-token-item[draggable="false"],
.npc-token-item.npc-on-map {
    cursor: default;
    opacity: 0.75;
}

.npc-token-item:not(.npc-on-map):hover {
    background: rgba(212, 175, 55, 0.1);
    border-color: #d4af37;
}

.npc-token-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
    border: 1px solid #5e4128;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #1b1b18;
}

.npc-token-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.npc-token-placeholder {
    font-size: 1.1rem;
}

.npc-token-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.npc-token-name {
    font-size: 0.78rem;
    color: #e4d8b4;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.npc-on-map-badge {
    font-size: 0.65rem;
    color: #d4af37;
}

.npc-token-actions {
    display: flex;
    flex-direction: column;
    gap: 2px;
    flex-shrink: 0;
}

.npc-action-btn {
    background: none;
    border: none;
    color: #8b5a2b;
    font-size: 0.85rem;
    cursor: pointer;
    padding: 1px 4px;
    border-radius: 3px;
    line-height: 1;
    transition: color 0.15s;
}

.npc-action-btn:hover {
    color: #d4af37;
}

.npc-remove-btn {
    background: none;
    border: none;
    color: #8b5a2b;
    font-size: 0.75rem;
    cursor: pointer;
    padding: 1px 4px;
    border-radius: 3px;
    line-height: 1;
    transition: color 0.15s;
}

.npc-remove-btn:hover {
    color: #e74c3c;
}
</style>
