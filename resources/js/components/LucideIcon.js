import { h } from 'vue';
import { lucideIconNodes } from '../lucide-icons.js';

export default {
  name: 'LucideIcon',
  inheritAttrs: false,
  props: {
    name: {
      type: String,
      required: true,
    },
  },
  render() {
    const iconNode = lucideIconNodes[this.name];
    if (!iconNode) {
      throw new Error(`Unknown Lucide icon: ${this.name}`);
    }

    return h(
      'svg',
      {
        xmlns: 'http://www.w3.org/2000/svg',
        width: '1em',
        height: '1em',
        viewBox: '0 0 24 24',
        fill: 'none',
        stroke: 'currentColor',
        'stroke-width': 1.8,
        'stroke-linecap': 'round',
        'stroke-linejoin': 'round',
        ...this.$attrs,
        class: ['lucide', `lucide-${this.name}`, this.$attrs.class],
        'aria-hidden': this.$attrs['aria-hidden'] ?? 'true',
      },
      iconNode.map(([tag, attrs]) => h(tag, attrs)),
    );
  },
};
