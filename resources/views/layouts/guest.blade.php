<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>SISMEN - Document System</title>
        <link rel="icon" type="image/png" href="{{ asset('favicon-logo.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('favicon-logo.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('favicon-logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700,800&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
            @keyframes blob {
                0% {
                    transform: translate(0px, 0px) scale(1);
                }
                33% {
                    transform: translate(40px, -60px) scale(1.15);
                }
                66% {
                    transform: translate(-30px, 30px) scale(0.9);
                }
                100% {
                    transform: translate(0px, 0px) scale(1);
                }
            }
            .animate-blob {
                animation: blob 12s infinite ease-in-out;
            }
            .animation-delay-2000 {
                animation-delay: 2s;
            }
            .animation-delay-4000 {
                animation-delay: 4s;
            }
        </style>
    </head>
    <body class="text-slate-900 antialiased bg-gradient-to-br from-white via-[#f0fbf6] to-[#e6f7ef] min-h-screen relative overflow-x-hidden flex items-center justify-center">
        <!-- Canvas Background Animation -->
        <canvas id="particle-canvas" class="absolute inset-0 w-full h-full pointer-events-none -z-20"></canvas>

        <!-- Floating Animated Background Blobs -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none -z-10">
            <div class="absolute top-[10%] left-[15%] w-80 h-80 bg-emerald-450/8 rounded-full blur-[90px] animate-blob" style="background-color: rgba(16, 185, 129, 0.08);"></div>
            <div class="absolute bottom-[15%] right-[10%] w-96 h-96 bg-teal-400/8 rounded-full blur-[110px] animate-blob animation-delay-2000" style="background-color: rgba(45, 212, 191, 0.08);"></div>
            <div class="absolute top-[40%] right-[25%] w-72 h-72 bg-green-400/8 rounded-full blur-[80px] animate-blob animation-delay-4000" style="background-color: rgba(74, 222, 128, 0.08);"></div>
        </div>

        {{ $slot }}

        <!-- Particle Animation Script -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const canvas = document.getElementById('particle-canvas');
                if (!canvas) return;
                const ctx = canvas.getContext('2d');

                let particles = [];
                let animationFrameId;
                
                // Track mouse position
                const mouse = {
                    x: null,
                    y: null,
                    radius: 120 // Radius of influence
                };

                window.addEventListener('mousemove', (e) => {
                    mouse.x = e.clientX;
                    mouse.y = e.clientY;
                });

                window.addEventListener('mouseout', () => {
                    mouse.x = null;
                    mouse.y = null;
                });

                // Resize canvas and fit pixel ratio
                function resizeCanvas() {
                    const rect = canvas.getBoundingClientRect();
                    const dpr = window.devicePixelRatio || 1;
                    canvas.width = rect.width * dpr;
                    canvas.height = rect.height * dpr;
                    ctx.scale(dpr, dpr);
                    initParticles();
                }

                // Particle Class
                class Particle {
                    constructor(x, y) {
                        this.x = x;
                        this.y = y;
                        // Slow movement speed suitable for premium background
                        this.vx = (Math.random() - 0.5) * 0.4;
                        this.vy = (Math.random() - 0.5) * 0.4;
                        this.radius = Math.random() * 1.5 + 1; // 1px to 2.5px
                        this.baseOpacity = Math.random() * 0.4 + 0.2;
                        this.opacity = this.baseOpacity;
                    }

                    update(width, height) {
                        // Drift
                        this.x += this.vx;
                        this.y += this.vy;

                        // Bounds collision
                        if (this.x < 0 || this.x > width) this.vx *= -1;
                        if (this.y < 0 || this.y > height) this.vy *= -1;

                        // Mouse interaction
                        if (mouse.x !== null && mouse.y !== null) {
                            const dx = this.x - mouse.x;
                            const dy = this.y - mouse.y;
                            const distance = Math.hypot(dx, dy);

                            if (distance < mouse.radius) {
                                // Push particles away slightly (repulsion)
                                const force = (mouse.radius - distance) / mouse.radius;
                                const angle = Math.atan2(dy, dx);
                                this.x += Math.cos(angle) * force * 1.2;
                                this.y += Math.sin(angle) * force * 1.2;
                                this.opacity = Math.min(0.8, this.baseOpacity + force * 0.4);
                            } else {
                                if (this.opacity > this.baseOpacity) {
                                    this.opacity -= 0.01;
                                }
                            }
                        } else {
                            if (this.opacity > this.baseOpacity) {
                                this.opacity -= 0.01;
                            }
                        }
                    }

                    draw() {
                        ctx.beginPath();
                        ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
                        ctx.fillStyle = `rgba(16, 185, 129, ${this.opacity})`; // light emerald-500
                        ctx.fill();
                    }
                }

                function initParticles() {
                    const width = canvas.width / (window.devicePixelRatio || 1);
                    const height = canvas.height / (window.devicePixelRatio || 1);
                    
                    // Particle count based on screen size
                    const area = width * height;
                    const count = Math.min(100, Math.floor(area / 16000));
                    
                    particles = [];
                    for (let i = 0; i < count; i++) {
                        particles.push(new Particle(
                            Math.random() * width,
                            Math.random() * height
                        ));
                    }
                }

                function drawConnections() {
                    const width = canvas.width / (window.devicePixelRatio || 1);
                    const height = canvas.height / (window.devicePixelRatio || 1);

                    for (let i = 0; i < particles.length; i++) {
                        for (let j = i + 1; j < particles.length; j++) {
                            const p1 = particles[i];
                            const p2 = particles[j];
                            const dx = p1.x - p2.x;
                            const dy = p1.y - p2.y;
                            const dist = Math.hypot(dx, dy);

                            const maxDist = 110;
                            if (dist < maxDist) {
                                const alpha = (1 - dist / maxDist) * 0.15;
                                ctx.beginPath();
                                ctx.moveTo(p1.x, p1.y);
                                ctx.lineTo(p2.x, p2.y);
                                ctx.strokeStyle = `rgba(16, 185, 129, ${alpha})`;
                                ctx.lineWidth = 0.5;
                                ctx.stroke();
                            }
                        }
                    }
                }

                function animate() {
                    const width = canvas.width / (window.devicePixelRatio || 1);
                    const height = canvas.height / (window.devicePixelRatio || 1);
                    
                    ctx.clearRect(0, 0, width, height);

                    particles.forEach(p => {
                        p.update(width, height);
                        p.draw();
                    });

                    drawConnections();
                    animationFrameId = requestAnimationFrame(animate);
                }

                window.addEventListener('resize', resizeCanvas);
                resizeCanvas();
                animate();
            });
        </script>
    </body>
</html>
