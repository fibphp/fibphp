package fibphp

type ValType uint8

type SizeT uint32

type SizePtr uint64

type SizeHash uint32

type compare_func_t func(interface{}, interface{}) int

type swap_func_t func(interface{}, interface{})

type sort_func_t func(interface{}, SizeT, SizeT, compare_func_t, swap_func_t)

type dtor_func_t func(pDest *FibVal)

type copy_ctor_func_t func(pElement *FibVal)


typedef union _fib_value {
fib_long         lval;				/* long value */
double            dval;				/* double value */
fib_refcounted  *counted;
fib_string      *str;
fib_array       *arr;
fib_object      *obj;
fib_resource    *res;
fib_reference   *ref;
fib_ast_ref     *ast;
zval             *zv;
void             *ptr;
fib_class_entry *ce;
fib_function    *func;
struct {
uint32_t w1;
uint32_t w2;
} ww;
} fib_value;

struct _zval_struct {
fib_value        value;			/* value */
union {
struct {
FIB_ENDIAN_LOHI_4(
fib_uchar    type,			/* active type */
fib_uchar    type_flags,
fib_uchar    const_flags,
fib_uchar    reserved)	    /* call info for EX(This) */
} v;
uint32_t type_info;
} u1;
union {
uint32_t     next;                 /* hash collision chain */
uint32_t     cache_slot;           /* literal cache slot */
uint32_t     lineno;               /* line number (for ast nodes) */
uint32_t     num_args;             /* arguments number for EX(This) */
uint32_t     fe_pos;               /* foreach position */
uint32_t     fe_iter_idx;          /* foreach iterator index */
uint32_t     access_flags;         /* class constant access flags */
uint32_t     property_guard;       /* single property guard */
uint32_t     extra;                /* not further specified */
} u2;
};

typedef struct _fib_refcounted_h {
uint32_t         refcount;			/* reference counter 32-bit */
union {
struct {
FIB_ENDIAN_LOHI_3(
fib_uchar    type,
fib_uchar    flags,    /* used for strings & objects */
uint16_t      gc_info)  /* keeps GC root number (or 0) and color */
} v;
uint32_t type_info;
} u;
} fib_refcounted_h;

struct _fib_refcounted {
fib_refcounted_h gc;
};

struct _fib_string {
fib_refcounted_h gc;
fib_ulong        h;                /* hash value */
size_t            len;
char              val[1];
};

typedef struct _Bucket {
zval              val;
fib_ulong        h;                /* hash value (or numeric index)   */
fib_string      *key;              /* string key or NULL for numerics */
} Bucket;

typedef struct _fib_array HashTable;

struct _fib_array {
fib_refcounted_h gc;
union {
struct {
FIB_ENDIAN_LOHI_4(
fib_uchar    flags,
fib_uchar    nApplyCount,
fib_uchar    nIteratorsCount,
fib_uchar    consistency)
} v;
uint32_t flags;
} u;
uint32_t          nTableMask;
Bucket           *arData;
uint32_t          nNumUsed;
uint32_t          nNumOfElements;
uint32_t          nTableSize;
uint32_t          nInternalPointer;
fib_long         nNextFreeElement;
dtor_func_t       pDestructor;
};

/*
 * HashTable Data Layout
 * =====================
 *
 *                 +=============================+
 *                 | HT_HASH(ht, ht->nTableMask) |
 *                 | ...                         |
 *                 | HT_HASH(ht, -1)             |
 *                 +-----------------------------+
 * ht->arData ---> | Bucket[0]                   |
 *                 | ...                         |
 *                 | Bucket[ht->nTableSize-1]    |
 *                 +=============================+
 */




typedef uint32_t HashPosition;

typedef struct _HashTableIterator {
	HashTable    *ht;
	HashPosition  pos;
} HashTableIterator;

struct _fib_object {
	fib_refcounted_h gc;
	uint32_t          handle; // TODO: may be removed ???
	fib_class_entry *ce;
	const fib_object_handlers *handlers;
	HashTable        *properties;
	zval              properties_table[1];
};

struct _fib_resource {
	fib_refcounted_h gc;
	int               handle; // TODO: may be removed ???
	int               type;
	void             *ptr;
};

struct _fib_reference {
	fib_refcounted_h gc;
	zval              val;
};

struct _fib_ast_ref {
	fib_refcounted_h gc;
	fib_ast         *ast;
};

/* regular data types */
#define IS_UNDEF					0
#define IS_NULL						1
#define IS_FALSE					2
#define IS_TRUE						3
#define IS_LONG						4
#define IS_DOUBLE					5
#define IS_STRING					6
#define IS_ARRAY					7
#define IS_OBJECT					8
#define IS_RESOURCE					9
#define IS_REFERENCE				10

/* constant expressions */
#define IS_CONSTANT					11
#define IS_CONSTANT_AST				12

/* fake types */
#define _IS_BOOL					13
#define IS_CALLABLE					14
#define IS_ITERABLE					19
#define IS_VOID						18

/* internal types */
#define IS_INDIRECT             	15
#define IS_PTR						17
#define _IS_ERROR					20

static fib_always_inline fib_uchar zval_get_type(const zval* pz) {
	return pz->u1.v.type;
}


/* zval.u1.v.type_flags */
#define IS_TYPE_CONSTANT			(1<<0)
#define IS_TYPE_REFCOUNTED			(1<<2)
#define IS_TYPE_COPYABLE			(1<<4)


/* extended types */
#define IS_INTERNED_STRING_EX		IS_STRING

#define IS_STRING_EX				(IS_STRING         | ((                   IS_TYPE_REFCOUNTED | IS_TYPE_COPYABLE) << Z_TYPE_FLAGS_SHIFT))
#define IS_ARRAY_EX					(IS_ARRAY          | ((                   IS_TYPE_REFCOUNTED | IS_TYPE_COPYABLE) << Z_TYPE_FLAGS_SHIFT))
#define IS_OBJECT_EX				(IS_OBJECT         | ((                   IS_TYPE_REFCOUNTED                   ) << Z_TYPE_FLAGS_SHIFT))
#define IS_RESOURCE_EX				(IS_RESOURCE       | ((                   IS_TYPE_REFCOUNTED                   ) << Z_TYPE_FLAGS_SHIFT))
#define IS_REFERENCE_EX				(IS_REFERENCE      | ((                   IS_TYPE_REFCOUNTED                   ) << Z_TYPE_FLAGS_SHIFT))

#define IS_CONSTANT_EX				(IS_CONSTANT       | ((IS_TYPE_CONSTANT | IS_TYPE_REFCOUNTED | IS_TYPE_COPYABLE) << Z_TYPE_FLAGS_SHIFT))
#define IS_CONSTANT_AST_EX			(IS_CONSTANT_AST   | ((IS_TYPE_CONSTANT | IS_TYPE_REFCOUNTED | IS_TYPE_COPYABLE) << Z_TYPE_FLAGS_SHIFT))

/* zval.u1.v.const_flags */
#define IS_CONSTANT_UNQUALIFIED		0x010
#define IS_CONSTANT_VISITED_MARK	0x020
#define IS_CONSTANT_CLASS           0x080  /* __CLASS__ in trait */
#define IS_CONSTANT_IN_NAMESPACE	0x100  /* used only in opline->extended_value */

#define IS_CONSTANT_VISITED(p)		(Z_CONST_FLAGS_P(p) & IS_CONSTANT_VISITED_MARK)
#define MARK_CONSTANT_VISITED(p)	Z_CONST_FLAGS_P(p) |= IS_CONSTANT_VISITED_MARK
#define RESET_CONSTANT_VISITED(p)	Z_CONST_FLAGS_P(p) &= ~IS_CONSTANT_VISITED_MARK

/* string flags (zval.value->gc.u.flags) */
#define IS_STR_PERSISTENT			(1<<0) /* allocated using malloc   */
#define IS_STR_INTERNED				(1<<1) /* interned string          */
#define IS_STR_PERMANENT        	(1<<2) /* relives request boundary */

#define IS_STR_CONSTANT             (1<<3) /* constant index */
#define IS_STR_CONSTANT_UNQUALIFIED (1<<4) /* the same as IS_CONSTANT_UNQUALIFIED */

/* array flags */
#define IS_ARRAY_IMMUTABLE			(1<<1)

/* object flags (zval.value->gc.u.flags) */
#define IS_OBJ_APPLY_COUNT			0x07
#define IS_OBJ_DESTRUCTOR_CALLED	(1<<3)
#define IS_OBJ_FREE_CALLED			(1<<4)
#define IS_OBJ_USE_GUARDS           (1<<5)
#define IS_OBJ_HAS_GUARDS           (1<<6)



static fib_always_inline uint32_t zval_refcount_p(zval* pz) {
	FIB_ASSERT(Z_REFCOUNTED_P(pz) || Z_COPYABLE_P(pz));
	return GC_REFCOUNT(Z_COUNTED_P(pz));
}

static fib_always_inline uint32_t zval_set_refcount_p(zval* pz, uint32_t rc) {
	FIB_ASSERT(Z_REFCOUNTED_P(pz));
	return GC_REFCOUNT(Z_COUNTED_P(pz)) = rc;
}

static fib_always_inline uint32_t zval_addref_p(zval* pz) {
	FIB_ASSERT(Z_REFCOUNTED_P(pz));
	return ++GC_REFCOUNT(Z_COUNTED_P(pz));
}

static fib_always_inline uint32_t zval_delref_p(zval* pz) {
	FIB_ASSERT(Z_REFCOUNTED_P(pz));
	return --GC_REFCOUNT(Z_COUNTED_P(pz));
}


const (
	/* regular data types */
	IS_UNDEF     ValType = iota
	IS_NULL
	IS_FALSE
	IS_TRUE
	IS_LONG
	IS_DOUBLE
	IS_STRING
	IS_ARRAY
	IS_OBJECT
	IS_RESOURCE
	iS_REFERENCE

	/* constant expressions */
	IS_CONSTANT
	IS_CONSTANT_AST

	/* fake types */
	IS_BOOL
	IS_CALLABLE

	/* internal types */
	IS_INDIRECT
	IS_PTR
)

type FibVal struct {
	Type       ValType
	TypeFlags  uint8
	ConstFlags uint8
	Reserved   uint8
	value      interface{}
	u2         uint32 // 大小对齐到16byte
}

type fibGcInfo struct {
	GcType  uint8
	GcFlags uint8
	GcInfo  uint16
}

type FibLong uint64

type FibDouble float64

type FibObject struct {
	gc *fibGcInfo
}

type FibResource struct {
	gc  *fibGcInfo
	t   uint32
	ptr interface{}
}

/*
引用是PHP中比较特殊的一种类型，它实际是指向另外一个PHP变量，对它的修改会直接改动实际指向的zval，可以简单的理解为C中的指针，在PHP中通过&操作符产生一个引用变量，也就是说不管以前的类型是什么，&首先会创建一个zend_reference结构，其内嵌了一个zval，这个zval的value指向原来zval的value(如果是布尔、整形、浮点则直接复制原来的值)，然后将原zval的类型修改为IS_REFERENCE，原zval的value指向新创建的zend_reference结构。
*/
type FibReference struct {
	gc  *fibGcInfo
	val *FibVal
}

type FibString struct {
	gc    *fibGcInfo
	value string
	h     SizeHash
}

type  HashPosition SizeT

type FibArrayFlags struct {
	flags           uint8
	nApplyCount     uint8
	nIteratorsCount uint8
	reserve         uint8
}

type FibArray struct {
	gc               *fibGcInfo
	flags            FibArrayFlags
	nTableMask       uint32  //哈希值计算掩码，等于nTableSize的负值(nTableMask = -nTableSize)
	arData           *Bucket //存储元素数组，指向第一个Bucket
	nNumUsed         SizeT   //已用Bucket数
	nNumOfElements   SizeT   //哈希表有效元素数
	nTableSize       SizeT   //哈希表总大小，为2的n次方
	nInternalPointer uint32
	nNextFreeElement FibLong //下一个可用的数值索引,如:arr[] = 1;arr["a"] = 2;arr[] = 3;  则nNextFreeElement = 2;
	pDestructor      dtor_func_t
}

type Bucket struct {
	val FibVal    //存储的具体value，这里嵌入了一个 FibVal，而不是一个指针
	h   SizeHash  //key根据times 33计算得到的哈希值，或者是数值索引编号
	key FibString //存储元素的key
}


