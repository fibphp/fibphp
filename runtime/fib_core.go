package fibphp

type ValType uint8

/*
// regular data types
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

// constant expressions
#define IS_CONSTANT_AST				11

// internal types
#define IS_INDIRECT             	13
#define IS_PTR						14
#define _IS_ERROR					15

// fake types used only for type hinting (Z_TYPE(zv) can not use them)
#define _IS_BOOL					16
#define IS_CALLABLE					17
#define IS_ITERABLE					18
#define IS_VOID						19
#define _IS_NUMBER					20
*/
const (
	/* regular data types */
	IS_UNDEF ValType = iota
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

/*
typedef union _zend_value {
	zend_long         lval;				// long value
	double            dval;				// double value
	zend_refcounted  *counted;
	zend_string      *str;
	zend_array       *arr;
	zend_object      *obj;
	zend_resource    *res;
	zend_reference   *ref;
	zend_ast_ref     *ast;
	zval             *zv;
	void             *ptr;
	zend_class_entry *ce;
	zend_function    *func;
	struct {
		uint32_t w1;
		uint32_t w2;
	} ww;
} zend_value;
*/
type ZendValue interface{}

/*
typedef struct _zval_struct     zval;

struct _zval_struct {
	zend_value        value;			// value
	union {
		struct {
			ZEND_ENDIAN_LOHI_3(
				zend_uchar    type,			// active type
				zend_uchar    type_flags,
				union {
					uint16_t  call_info;    // call info for EX(This)
					uint16_t  extra;        // not further specified
				} u)
		} v;
		uint32_t type_info;
	} u1;
	union {
		uint32_t     next;                 // hash collision chain
		uint32_t     cache_slot;           // cache slot (for RECV_INIT)
		uint32_t     opline_num;           // opline number (for FAST_CALL)
		uint32_t     lineno;               // line number (for ast nodes)
		uint32_t     num_args;             // arguments number for EX(This)
		uint32_t     fe_pos;               // foreach position
		uint32_t     fe_iter_idx;          // foreach iterator index
		uint32_t     access_flags;         // class constant access flags
		uint32_t     property_guard;       // single property guard
		uint32_t     constant_flags;       // constant flags
		uint32_t     extra;                // not further specified
	} u2;
};
*/
type TypeInfo struct {
	Type      ValType
	TypeFlags uint8
	CallInfo  uint16
}

type ZVal struct {
	Value ZendValue
	TypeInfo
	U2 uint32
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

type HashPosition SizeT

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
