console.log('Editor script loaded');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded');
    const form = document.getElementById('jersey-form');
    const textureLayer = document.getElementById('texture-layer');
    const ornamentsLayer = document.getElementById('ornaments-layer');
    const textureSizeSlider = document.getElementById('texture-size-slider');
    const textureSizeValue = document.getElementById('texture-size-value');
    const textureUrlInput = document.getElementById('texture-url');
    const textureSizeInput = document.getElementById('texture-size');
    const ornamentsDataInput = document.getElementById('ornaments-data');
    const textureHueSlider = document.getElementById('texture-hue-slider');
    const textureHueValue = document.getElementById('texture-hue-value');
    const generateButton = document.getElementById('generate-textures');
    const promptTextarea = document.querySelector('textarea[name="prompt"]');

    console.log('Elements:', {
        textureLayer,
        textureSizeSlider,
        textureUrlInput
    });

    // Store current state
    let currentTextureUrl = '';
    let currentTextureSize = 100;
    let currentTextureHue = 0;
    let currentOrnaments = {};
    let texturePosition = { x: 0, y: 0 };

    // Texture handling
    const textureButtons = document.querySelectorAll('.texture-option');
    console.log('Found texture buttons:', textureButtons.length);
    
    textureButtons.forEach(button => {
        console.log('Adding click listener to texture button');
        button.addEventListener('click', function() {
            console.log('Texture button clicked');
            const textureUrl = this.dataset.textureUrl;
            console.log('Texture URL:', textureUrl);
            
            currentTextureUrl = textureUrl;
            textureUrlInput.value = textureUrl;
            
            console.log('Setting background image:', `url('${textureUrl}')`);
            textureLayer.style.backgroundImage = `url('${textureUrl}')`;
            textureLayer.style.backgroundSize = `${currentTextureSize}%`;
            textureLayer.style.backgroundRepeat = 'repeat';
            textureLayer.style.opacity = '1';
            textureLayer.style.backgroundPosition = `${texturePosition.x}px ${texturePosition.y}px`;
            textureLayer.style.filter = `hue-rotate(${currentTextureHue}deg)`;
            
            console.log('Texture layer styles:', {
                backgroundImage: textureLayer.style.backgroundImage,
                backgroundSize: textureLayer.style.backgroundSize,
                opacity: textureLayer.style.opacity,
                backgroundPosition: textureLayer.style.backgroundPosition,
                filter: textureLayer.style.filter
            });
        });
    });

    // Texture size slider
    textureSizeSlider.addEventListener('input', function() {
        const size = this.value;
        currentTextureSize = size;
        textureSizeInput.value = size;
        textureSizeValue.textContent = `${size}%`;
        if (currentTextureUrl) {
            textureLayer.style.backgroundSize = `${size}%`;
        }
    });

    // Texture hue slider
    textureHueSlider.addEventListener('input', function() {
        const hue = this.value;
        currentTextureHue = hue;
        textureHueValue.textContent = `${hue}°`;
        if (currentTextureUrl) {
            textureLayer.style.filter = `hue-rotate(${hue}deg)`;
        }
    });

    // Texture position with arrow keys and buttons
    function moveTexture(direction) {
        if (!currentTextureUrl) return; // Only move if a texture is selected
        
        const step = 10; // Pixels to move per keypress
        
        switch(direction) {
            case 'up':
                texturePosition.y -= step;
                break;
            case 'down':
                texturePosition.y += step;
                break;
            case 'left':
                texturePosition.x -= step;
                break;
            case 'right':
                texturePosition.x += step;
                break;
        }
        
        textureLayer.style.backgroundPosition = `${texturePosition.x}px ${texturePosition.y}px`;
        console.log('Texture position:', texturePosition);
    }

    // Arrow keys
    document.addEventListener('keydown', function(e) {
        if (!currentTextureUrl) return;
        
        switch(e.key) {
            case 'ArrowUp':
                moveTexture('up');
                break;
            case 'ArrowDown':
                moveTexture('down');
                break;
            case 'ArrowLeft':
                moveTexture('left');
                break;
            case 'ArrowRight':
                moveTexture('right');
                break;
        }
    });

    // Arrow buttons
    document.querySelectorAll('.texture-move').forEach(button => {
        button.addEventListener('click', function() {
            const direction = this.dataset.direction;
            moveTexture(direction);
        });
    });

    // Function to update ornaments layer
    function updateOrnamentsLayer() {
        console.log('Updating ornaments layer...');
        ornamentsLayer.innerHTML = ''; // Clear existing ornaments
        
        Object.entries(currentOrnaments).forEach(([ornamentId, ornament]) => {
            console.log('Adding ornament to layer:', ornamentId, ornament);
            const img = document.createElement('img');
            img.src = ornament.image_url;
            img.alt = `Ornament ${ornamentId}`;
            img.className = 'absolute inset-0 w-full h-full object-contain z-40';
            ornamentsLayer.appendChild(img);
        });
    }

    // Initialize ornaments layer with first versions
    function initializeOrnaments() {
        console.log('Initializing ornaments...');
        const ornamentContainers = document.querySelectorAll('[data-ornament-id]');
        console.log('Found ornament containers:', ornamentContainers.length);

        if (!ornamentContainers.length) {
            console.log('No ornament containers found');
            return;
        }

        ornamentContainers.forEach(container => {
            const ornamentId = container.dataset.ornamentId;
            console.log('Processing ornament:', ornamentId);

            const preview = container.querySelector('.version-preview img');
            if (!preview) {
                console.error('No preview image found for ornament:', ornamentId);
                return;
            }

            try {
                const versions = JSON.parse(preview.dataset.versions);
                console.log('Versions for ornament', ornamentId, ':', versions);

                if (versions && versions.length > 0) {
                    const firstVersion = versions[0];
                    console.log('First version for ornament', ornamentId, ':', firstVersion);

                    currentOrnaments[ornamentId] = {
                        version_id: firstVersion.id,
                        image_url: firstVersion.image_url
                    };
                    console.log('Added ornament to currentOrnaments:', currentOrnaments[ornamentId]);
                } else {
                    console.log('No versions available for ornament:', ornamentId);
                }
            } catch (error) {
                console.error('Error processing ornament', ornamentId, ':', error);
            }
        });

        console.log('Final currentOrnaments:', currentOrnaments);
        updateOrnamentsLayer();
    }

    // Ornament version navigation
    function setupOrnamentNavigation() {
        document.querySelectorAll('.version-prev, .version-next').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const ornamentId = this.dataset.ornamentId;
                if (!ornamentId) {
                    console.error('No ornament ID found');
                    return;
                }

                const isPrev = this.classList.contains('version-prev');
                const container = this.closest('.border.rounded-lg.p-3');
                if (!container) {
                    console.error('No container found for ornament:', ornamentId);
                    return;
                }

                const preview = container.querySelector('.version-preview img');
                console.log('Container:', container);
                if (!preview) {
                    console.error('No preview image found for ornament:', ornamentId);
                    return;
                }

                const currentVersionId = preview.dataset.currentVersion;
                let versions;
                try {
                    versions = JSON.parse(preview.dataset.versions);
                } catch (error) {
                    console.error('Error parsing versions:', error);
                    return;
                }
                
                console.log('Ornament navigation clicked:', {
                    ornamentId,
                    isPrev,
                    currentVersionId,
                    versions
                });
                
                if (!versions || versions.length === 0) {
                    console.log('No versions available');
                    return;
                }

                const currentIndex = versions.findIndex(v => v.id === parseInt(currentVersionId));
                let newIndex;
                
                if (isPrev) {
                    newIndex = (currentIndex - 1 + versions.length) % versions.length;
                } else {
                    newIndex = (currentIndex + 1) % versions.length;
                }
                
                const newVersion = versions[newIndex];
                console.log('New version:', newVersion);
                
                preview.src = newVersion.image_url;
                preview.dataset.currentVersion = newVersion.id;
                
                // Update ornaments data
                currentOrnaments[ornamentId] = {
                    version_id: newVersion.id,
                    image_url: newVersion.image_url
                };
                ornamentsDataInput.value = JSON.stringify(currentOrnaments);

                // Update ornaments layer
                updateOrnamentsLayer();
            });
        });
    }

    // Handle texture generation
    generateButton.addEventListener('click', async function() {
        const prompt = promptTextarea.value.trim();
        console.log('Prompt:', prompt);
        
        if (!prompt) {
            alert('Veuillez entrer une description pour générer les textures');
            return;
        }

        // Désactiver le bouton et ajouter le loader
        generateButton.disabled = true;
        generateButton.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Génération en cours...
        `;

        try {
            const response = await fetch('/editor/send-prompt', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ prompt })
            });

            if (!response.ok) {
                throw new Error('Erreur lors de la génération des textures');
            }

            const data = await response.json();
            console.log('Generated textures:', data);

            // Update texture buttons with generated images
            const textureButtons = document.querySelectorAll('.texture-option');
            data.textures.forEach((textureUrl, index) => {
                if (textureButtons[index]) {
                    const img = textureButtons[index].querySelector('img');
                    img.src = textureUrl;
                    textureButtons[index].dataset.textureUrl = textureUrl;
                }
            });
        } catch (error) {
            console.error('Error:', error);
            alert('Une erreur est survenue lors de la génération des textures');
        } finally {
            // Réactiver le bouton et remettre le texte original
            generateButton.disabled = false;
            generateButton.innerHTML = 'Générer';
        }
    });

    // Initialize everything
    initializeOrnaments();
    setupOrnamentNavigation();

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Ensure all required data is set
        if (!currentTextureUrl) {
            alert('Please select a texture');
            return;
        }
        
        if (Object.keys(currentOrnaments).length === 0) {
            alert('Please select at least one ornament');
            return;
        }
        
        this.submit();
    });
}); 