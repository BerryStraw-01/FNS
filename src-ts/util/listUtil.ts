export class AccessOrderMap<K, V> {
    private map = new Map<K, V>()

    get(key: K): V {
        const result = this.map.get(key)
        this.set(key, result)
        return result
    }

    set(key: K, value: V) {
        this.delete(key)
        this.map.set(key, value)
    }

    delete(key: K) {
        this.map.delete(key)
    }

    size(): number {
        return this.map.size
    }

    deleteFinal() {
        let index = 0;
        for (const key of this.map.keys()) {
            index++
            if (index == this.map.size) {
                this.delete(key)
            }
        }
    }
}

export class LimitedSizeMap<K, V> extends AccessOrderMap<K, V> {
    private readonly maxsize: number

    constructor(maxsize: number = 50) {
        super();
        this.maxsize = maxsize
    }

    override set(key: K, value: V) {
        this.adapt()
        super.set(key, value);
    }

    private adapt() {
        if (this.size() > this.maxsize) {
            this.deleteFinal()
        }
    }
}