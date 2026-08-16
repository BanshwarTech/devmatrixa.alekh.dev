// Ported from the Next.js source at lib/cssToTailwind.ts.
// Runs entirely client-side — no controller/API route for this tool.

const SPACING = {
  0: '0', 1: 'px', 2: '0.5', 4: '1', 6: '1.5', 8: '2', 10: '2.5', 12: '3', 14: '3.5',
  16: '4', 20: '5', 24: '6', 28: '7', 32: '8', 36: '9', 40: '10', 44: '11', 48: '12',
  56: '14', 64: '16', 80: '20', 96: '24', 112: '28', 128: '32', 144: '36', 160: '40',
  176: '44', 192: '48', 208: '52', 224: '56', 240: '60', 256: '64', 288: '72', 320: '80', 384: '96',
};

const FONT_SIZES = {
  12: 'xs', 14: 'sm', 16: 'base', 18: 'lg', 20: 'xl', 24: '2xl',
  30: '3xl', 36: '4xl', 48: '5xl', 60: '6xl', 72: '7xl', 96: '8xl', 128: '9xl',
};

const STATIC = {
  'display:flex': 'flex', 'display:inline-flex': 'inline-flex', 'display:grid': 'grid',
  'display:inline-grid': 'inline-grid', 'display:block': 'block', 'display:inline-block': 'inline-block',
  'display:inline': 'inline', 'display:none': 'hidden', 'display:table': 'table',
  'display:contents': 'contents', 'display:flow-root': 'flow-root',
  'position:static': 'static', 'position:fixed': 'fixed', 'position:absolute': 'absolute',
  'position:relative': 'relative', 'position:sticky': 'sticky',
  'overflow:auto': 'overflow-auto', 'overflow:hidden': 'overflow-hidden',
  'overflow:visible': 'overflow-visible', 'overflow:scroll': 'overflow-scroll',
  'overflow-x:auto': 'overflow-x-auto', 'overflow-x:hidden': 'overflow-x-hidden',
  'overflow-y:auto': 'overflow-y-auto', 'overflow-y:hidden': 'overflow-y-hidden',
  'flex-direction:row': 'flex-row', 'flex-direction:row-reverse': 'flex-row-reverse',
  'flex-direction:column': 'flex-col', 'flex-direction:column-reverse': 'flex-col-reverse',
  'flex-wrap:wrap': 'flex-wrap', 'flex-wrap:nowrap': 'flex-nowrap',
  'flex:1': 'flex-1', 'flex:auto': 'flex-auto', 'flex:none': 'flex-none',
  'flex-grow:1': 'grow', 'flex-grow:0': 'grow-0', 'flex-shrink:1': 'shrink', 'flex-shrink:0': 'shrink-0',
  'align-items:flex-start': 'items-start', 'align-items:flex-end': 'items-end',
  'align-items:center': 'items-center', 'align-items:baseline': 'items-baseline',
  'align-items:stretch': 'items-stretch',
  'justify-content:flex-start': 'justify-start', 'justify-content:flex-end': 'justify-end',
  'justify-content:center': 'justify-center', 'justify-content:space-between': 'justify-between',
  'justify-content:space-around': 'justify-around', 'justify-content:space-evenly': 'justify-evenly',
  'width:100%': 'w-full', 'width:100vw': 'w-screen', 'width:auto': 'w-auto',
  'height:100%': 'h-full', 'height:100vh': 'h-screen', 'height:auto': 'h-auto',
  'min-width:0': 'min-w-0', 'min-width:100%': 'min-w-full',
  'max-width:none': 'max-w-none', 'max-width:100%': 'max-w-full',
  'font-weight:100': 'font-thin', 'font-weight:200': 'font-extralight',
  'font-weight:300': 'font-light', 'font-weight:400': 'font-normal',
  'font-weight:500': 'font-medium', 'font-weight:600': 'font-semibold',
  'font-weight:700': 'font-bold', 'font-weight:800': 'font-extrabold', 'font-weight:900': 'font-black',
  'font-weight:bold': 'font-bold', 'font-weight:normal': 'font-normal',
  'font-style:italic': 'italic', 'font-style:normal': 'not-italic',
  'text-align:left': 'text-left', 'text-align:center': 'text-center',
  'text-align:right': 'text-right', 'text-align:justify': 'text-justify',
  'text-transform:uppercase': 'uppercase', 'text-transform:lowercase': 'lowercase',
  'text-transform:capitalize': 'capitalize', 'text-transform:none': 'normal-case',
  'text-decoration:underline': 'underline', 'text-decoration:line-through': 'line-through',
  'text-decoration:none': 'no-underline',
  'line-height:1': 'leading-none', 'line-height:1.25': 'leading-tight',
  'line-height:1.5': 'leading-normal', 'line-height:1.625': 'leading-relaxed',
  'line-height:2': 'leading-loose',
  'white-space:nowrap': 'whitespace-nowrap', 'white-space:normal': 'whitespace-normal',
  'white-space:pre': 'whitespace-pre',
  'border-style:solid': 'border-solid', 'border-style:dashed': 'border-dashed',
  'border-style:dotted': 'border-dotted', 'border-style:none': 'border-none',
  'border:none': 'border-0', 'border:0': 'border-0',
  'border-radius:9999px': 'rounded-full', 'border-radius:0': 'rounded-none',
  'border-radius:0.125rem': 'rounded-sm', 'border-radius:0.25rem': 'rounded',
  'border-radius:0.375rem': 'rounded-md', 'border-radius:0.5rem': 'rounded-lg',
  'border-radius:0.75rem': 'rounded-xl', 'border-radius:1rem': 'rounded-2xl',
  'border-radius:1.5rem': 'rounded-3xl', 'border-radius:50%': 'rounded-full',
  'opacity:0': 'opacity-0', 'opacity:1': 'opacity-100',
  'opacity:0.5': 'opacity-50', 'opacity:0.75': 'opacity-75', 'opacity:0.25': 'opacity-25',
  'cursor:pointer': 'cursor-pointer', 'cursor:not-allowed': 'cursor-not-allowed',
  'cursor:default': 'cursor-default', 'cursor:text': 'cursor-text',
  'pointer-events:none': 'pointer-events-none', 'pointer-events:auto': 'pointer-events-auto',
  'user-select:none': 'select-none', 'user-select:text': 'select-text',
  'visibility:visible': 'visible', 'visibility:hidden': 'invisible',
  'outline:none': 'outline-none', 'outline:0': 'outline-none',
  'object-fit:contain': 'object-contain', 'object-fit:cover': 'object-cover',
  'object-fit:fill': 'object-fill', 'object-fit:none': 'object-none',
  'float:left': 'float-left', 'float:right': 'float-right', 'float:none': 'float-none',
};

const SPACING_PREFIX = {
  padding: 'p', 'padding-top': 'pt', 'padding-right': 'pr', 'padding-bottom': 'pb', 'padding-left': 'pl',
  margin: 'm', 'margin-top': 'mt', 'margin-right': 'mr', 'margin-bottom': 'mb', 'margin-left': 'ml',
  gap: 'gap', 'column-gap': 'gap-x', 'row-gap': 'gap-y',
  top: 'top', right: 'right', bottom: 'bottom', left: 'left',
  width: 'w', height: 'h', 'min-width': 'min-w', 'min-height': 'min-h',
  'max-width': 'max-w', 'max-height': 'max-h',
};

function toPx(v) {
  const val = v.toLowerCase().trim();
  if (val === '0') return 0;
  let m = val.match(/^(-?[\d.]+)px$/);
  if (m) return parseFloat(m[1]);
  m = val.match(/^(-?[\d.]+)rem$/);
  if (m) return parseFloat(m[1]) * 16;
  m = val.match(/^(-?[\d.]+)em$/);
  if (m) return parseFloat(m[1]) * 16;
  return null;
}

function closest(target, map, threshold) {
  let best = null;
  let diff = Infinity;
  for (const [px, cls] of Object.entries(map)) {
    const d = Math.abs(parseFloat(px) - target);
    if (d < diff) {
      diff = d;
      best = cls;
    }
  }
  return diff <= threshold ? best : null;
}

function extractDeclarations(css) {
  let cleaned = css.replace(/\/\*[\s\S]*?\*\//g, '');
  if (cleaned.includes('{')) {
    const re = /\{([^}]+)\}/g;
    let body = '';
    let m;
    while ((m = re.exec(cleaned))) body += m[1] + ';';
    cleaned = body;
  }
  const decls = [];
  for (const line of cleaned.split(';')) {
    const t = line.trim();
    if (!t || !t.includes(':')) continue;
    const idx = t.indexOf(':');
    const prop = t.slice(0, idx).trim();
    const value = t.slice(idx + 1).trim();
    if (prop && value) decls.push({ property: prop, value });
  }
  return decls;
}

export function convertCssToTailwind(css) {
  const decls = extractDeclarations(css);
  const results = [];

  for (const d of decls) {
    const prop = d.property.toLowerCase();
    const val = d.value.toLowerCase();
    const key = `${prop}:${val}`;
    const noWs = `${prop}:${val.replace(/\s+/g, '')}`;

    if (STATIC[key] || STATIC[noWs]) {
      results.push({ property: d.property, value: d.value, tailwind: STATIC[key] ?? STATIC[noWs], matched: true });
      continue;
    }

    if (SPACING_PREFIX[prop]) {
      const px = toPx(val);
      if (px !== null) {
        const tw = closest(px, SPACING, 4);
        if (tw !== null) {
          let cls = `${SPACING_PREFIX[prop]}-${tw}`;
          if (px < 0 && ['margin', 'margin-top', 'margin-right', 'margin-bottom', 'margin-left'].includes(prop)) {
            cls = `-${SPACING_PREFIX[prop]}-${tw}`;
          }
          results.push({ property: d.property, value: d.value, tailwind: cls, matched: true });
          continue;
        }
      }
      if (val === 'auto') {
        results.push({ property: d.property, value: d.value, tailwind: `${SPACING_PREFIX[prop]}-auto`, matched: true });
        continue;
      }
    }

    if (prop === 'font-size') {
      const px = toPx(val);
      if (px !== null) {
        const tw = closest(Math.round(px), FONT_SIZES, 2);
        if (tw) {
          results.push({ property: d.property, value: d.value, tailwind: `text-${tw}`, matched: true });
          continue;
        }
      }
    }

    if (prop === 'z-index' && /^-?\d+$/.test(val)) {
      const z = parseInt(val, 10);
      const zMap = { 0: '0', 10: '10', 20: '20', 30: '30', 40: '40', 50: '50' };
      if (zMap[z]) {
        results.push({ property: d.property, value: d.value, tailwind: `z-${zMap[z]}`, matched: true });
        continue;
      }
    }

    const bm = prop.match(/^border(?:-(top|right|bottom|left))?-width$/);
    if (prop === 'border-width' || bm) {
      const px = toPx(val);
      if (px !== null) {
        const suffix = px === 1 ? '' : px === 2 ? '-2' : px === 4 ? '-4' : px === 8 ? '-8' : null;
        if (suffix !== null) {
          const sideMap = { top: 't', right: 'r', bottom: 'b', left: 'l' };
          const side = bm && bm[1] ? sideMap[bm[1]] : '';
          results.push({ property: d.property, value: d.value, tailwind: `border${side ? '-' + side : ''}${suffix}`, matched: true });
          continue;
        }
      }
    }

    results.push({ property: d.property, value: d.value, tailwind: null, matched: false });
  }

  const matched = results.filter((r) => r.matched);
  return {
    results,
    tailwindOutput: matched.map((r) => r.tailwind).join(' '),
    matched: matched.length,
    unmatched: results.length - matched.length,
  };
}
